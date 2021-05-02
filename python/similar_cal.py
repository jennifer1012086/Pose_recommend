import cv2
import numpy as np
from PIL import Image
import requests
from io import BytesIO
import matplotlib
matplotlib.use('TkAgg')
import matplotlib.pyplot as plt
import os
import sys
import time
import json
import base64
import heapq

def dHash(img):

    img = cv2.resize(img, (9, 8))
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    hash_str = ''
    for i in range(8):
        for j in range(8):
            if gray[i, j] > gray[i, j+1]:
                hash_str = hash_str+'1'
            else:
                hash_str = hash_str+'0'
    return hash_str
 
def getImageByUrl(url):
    html = requests.get(url, verify=False)
    image = Image.open(BytesIO(html.content))
    return image

 
def calculate(image1, image2):

    hist1 = cv2.calcHist([image1], [0], None, [256], [0.0, 255.0])
    hist2 = cv2.calcHist([image2], [0], None, [256], [0.0, 255.0])
    degree = 0
    for i in range(len(hist1)):
        if hist1[i] != hist2[i]:
            degree = degree + \
                (1 - abs(hist1[i] - hist2[i]) / max(hist1[i], hist2[i]))
        else:
            degree = degree + 1
    degree = degree / len(hist1)
    return degree
 
 
def classify_hist_with_split(image1, image2, size=(256, 256)):

    image1 = cv2.resize(image1, size)
    image2 = cv2.resize(image2, size)
    sub_image1 = cv2.split(image1)
    sub_image2 = cv2.split(image2)
    sub_data = 0
    for im1, im2 in zip(sub_image1, sub_image2):
        sub_data += calculate(im1, im2)
    sub_data = sub_data / 3
    return sub_data
 
 
def cmpHash(hash1, hash2):

    n = 0
    if len(hash1) != len(hash2):
        return -1
    for i in range(len(hash1)):
        if hash1[i] != hash2[i]:
            n = n + 1
    return n

def cal_similar_value(para1, para2):

    if para1.startswith("http"):
        img1 = getImageByUrl(para1)
        img1 = cv2.cvtColor(np.asarray(img1), cv2.COLOR_RGB2BGR)
 
        img2 = getImageByUrl(para2)
        img2 = cv2.cvtColor(np.asarray(img2), cv2.COLOR_RGB2BGR)
    else:
        img1 = cv2.imread(para1)
        img2 = cv2.imread(para2)
 
    hash1 = dHash(img1)
    hash2 = dHash(img2)
    n2 = cmpHash(hash1, hash2)

    return n2


def list_image(arg):
    pic_path = []
    pic_value = []
    for path in arg['path']:
        value = cal_similar_value(arg['upload'],path)
        name = path
        pic_path.append(path)
        pic_value.append(value)

    #find smallest 5
    similar_pic = []
    for i in range(5):
        similar_pic.append(pic_path[pic_value.index(min(pic_value))])
        pic_value[pic_value.index(min(pic_value))] = float('Inf')

    return similar_pic

    



def get_json(data):
    data = data.replace("\'", '\"')
    test = json.loads(base64.b64decode(data))
    #test = json.loads(data)
    #return json.dumps(test)
    return test

def trans_json(similar_pic):

    # similar = []
    # for path in similar_pic:
    #     similar.append('\"' + path + '\"')
    # image = ",".join(similar)

    output = {
        "length": len(similar_pic),
        "image": similar_pic
    }
    # string = '{"length":'+str(len(similar_pic))+',"image":['+image+']}'
    ret = json.dumps(output)
    
    return ret 

#python similar_cal.py {'length':2,'path':['t1.jpg','t2.jpg'],'upload':'t3.jpg'}
if __name__ == "__main__":

    argc = len(sys.argv)
    if argc < 2:
        print('Usage : python '+sys.argv[0]+' [-json format]')
    else:

        #str1 = '{"length":2,"path":["t1.jpg","t2.jpg"],"upload":"t3.jpg"}'
        #arg = get_json(str1)
        #print(arg)
        #similar_pic = list_image(arg) 
        similar_pic = list_image(get_json(sys.argv[1])) 

        ret = trans_json(similar_pic)
        print(ret)

        



    