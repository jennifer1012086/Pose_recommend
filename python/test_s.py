import sys, json, base64, random

a = json.loads(base64.b64decode(sys.argv[1]))

out_scene = []
out_scene.append(random.randint(1, 12))
out_scene.append(random.randint(1, 12))
out_scene.append(random.randint(1, 12))

x = {
    "length": len(out_scene),
    "scene": out_scene,
    "upload": a['upload']
}
print(json.dumps(x))
