# from sense_hat import SenseHat
# import time
# import requests

# sense = SenseHat()

# def get_temperature():
#     temp = sense.get_temperature()
#     return round(temp, 1)

# while True:
#     temperatuur = get_temperature()
#     response = requests.post("http://localhost/KBS-Webshop/api/addTemp.php", temperatuur=temperatuur) 
#     print(f"{temperatuur}")
#     time.sleep(3)

import requests
import time
import random

while True:
    waarde = round(random.uniform(1, 15), 1)
    data = {'waarde': waarde}  
    response = requests.post('http://localhost/KBS-Webshop/api/addTemp.php', data=data) 
    print(f"Verzoek verzonden met waarde: {waarde}")
    time.sleep(3)

