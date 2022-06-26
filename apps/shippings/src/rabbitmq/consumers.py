import json
import uuid
from pika.adapters.blocking_connection import BlockingChannel

from database.models import Shipping, ShippingStatus
from database import session

def create_shipping_consumer(body, ack, channel: BlockingChannel):
    print("create_shipping_consumer")
    print(json.dumps(body, indent=4, sort_keys=True))

    shipping = Shipping(
        order_id=body["orderId"],
        status=ShippingStatus.SUCCESS,
        external_shipping_id=uuid.uuid4(),
    )
    session.add(shipping)
    session.commit()

    channel.basic_publish("shippings", "shipping.success", json.dumps({
        "orderId": body["orderId"],
        "shippingId": shipping.to_dict()["id"],
    }))
    # channel.basic_publish("shippings", "shipping.failed", json.dumps({
    #     "orderId": body["orderId"],
    # }))

    ack()
