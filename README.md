# RTB-task

Implementing Real-Time Bidding (RTB) Native Campaign Response

## **How to Run**

### **1. Clone the Repository**

```bash
git clone https://github.com/prodhan482/RTB-task.git
cd RTB-task
```

### **2. Test For getting the response **

```bash
php test_request.php
```

#### ** The Response you Get **

```bash
{
    "id": "64dd7619-5723-450b-ab12-36b3367fae97",
    "bidid": "674fc956e9c42",
    "seatbid": [
        {
            "bid": [
                {
                    "price": 0.1000000000000000055511151231257827021181583404541015625,
                    "adm": "{\"native\":{\"assets\":[{\"id\":101,\"title\":{\"text\":\"GameStar\"},\"required\":1},{\"id\":104,\"img\":{\"url\":\"https:\\\/\\\/example.com\\\/image.jpg\",\"w\":600,\"h\":600},\"required\":1},{\"id\":102,\"data\":{\"value\":\"Play Tournament Game\",\"type\":2},\"required\":1},{\"id\":103,\"data\":{\"value\":\"PLAY\",\"type\":12},\"required\":1}],\"link\":{\"url\":\"https:\\\/\\\/gamestar.shabox.mobi\\\/\"},\"ver\":\"1.2\"}}",
                    "id": "674fc956e9c49",
                    "impid": "1",
                    "crid": 168962,
                    "bundle": "com.imo.android.imoim"
                }
            ],
            "seat": "1003",
            "group": 0
        }
    ]
}
```
