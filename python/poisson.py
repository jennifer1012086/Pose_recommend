import cv2
import numpy as np
import sys
import json
import base64

class img_data:
    def __init__(self,source,mask,upload,scale_rate,px,py):
        self.source = source
        self.mask = mask
        self.upload = upload
        self.scale_rate = scale_rate
        self.px = px
        self.py = py


def get_json(data):
     test = json.loads(base64.b64decode(data))
     # print(json.dumps(test))
     # print(json.dumps(test))
     return test


def poissson(img):
    #print(img)
    sour = cv2.imread(img.source)
    mask = cv2.imread(img.mask)
    upload = cv2.imread(img.upload)
    #print(sour)
    #print(upload.shape)
    
    #print(sour.shape)
    #print(img_data.mask)

    sour_width = int(sour.shape[0] * img.scale_rate)
    sour_height = int(sour.shape[1] * img.scale_rate)
    #mask_width = int(mask.shape[0] * img_data.scale_rate)
   #mask_height = int(mask.shape[1] * img_data.scale_rate)
    source_center = (int(sour_width/2),int(sour_height/2))
    #mask_center = (int(mask_width/2),int(mask_height/2))
    paste_center = (int(source_center[0]+img.px), int(source_center[1]+img.py))
    #print(paste_center)
    #dim = (width, height)

# center = (500, 500)

# print(center)
    sour = cv2.resize(sour, (sour_width,sour_height), interpolation = cv2.INTER_AREA)
    mask = cv2.resize(mask, (sour_width,sour_height), interpolation = cv2.INTER_AREA)
    #print(sour.shape,mask.shape)
    output = cv2.seamlessClone(sour, upload, mask, paste_center, cv2.MIXED_CLONE)
    output_path = "/app/images/upload/new.jpg"
    cv2.imwrite(output_path, output)  
    return output_path
    


if __name__ == "__main__":
    # print(sys.argv[1])
    data_json = get_json(sys.argv[1])
    #data_json = {
    #    "person": "../images/5.jpg",
    #    "mask": "../images/mask/5.jpg",
    #    "position_x": 2.5,
    #    "position_y": 3.6,
    #    "scale": 1.05,
    #    "upload": "../images/upload/tmp.jpg"
    #}
    #print(data_json)
    img = img_data(data_json["person"],data_json["mask"],
    data_json["upload"],data_json["scale"],data_json["position_x"],data_json["position_y"])
    path = poissson(img)
    #print(path)
    #data_json = get_json(sys.argv[1])
    
