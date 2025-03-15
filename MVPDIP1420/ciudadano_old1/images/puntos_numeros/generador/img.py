from PIL import Image
from PIL import ImageDraw
from PIL import ImageFont 



W, H = (512,512)
msg = "1"



for x in range(1,100):
    img = Image.open('puntos.png')
    I1 = ImageDraw.Draw(img)
    myFont = ImageFont.truetype('./lato/Lato-Black.ttf', 240)
    if x < 10:
        #? 1 digitos
        punto = 180,55
    else:
        #? dos digitos
        punto = 120,55
    I1.text(punto, str(x),font=myFont, fill="white") 
    img.show() 
    img.save( str(x)+".png")
    
exit(0)










img.show()
img.save("car2.png")