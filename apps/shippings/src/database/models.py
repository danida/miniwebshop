from enum import Enum
from uuid import uuid4

from sqlalchemy import Column
from sqlalchemy import Enum as SQLAEnum
from sqlalchemy import Integer, String
from sqlalchemy.orm import declarative_base
from sqlalchemy_serializer import SerializerMixin
from sqlalchemy_utils import UUIDType

from database import engine

Base = declarative_base()

class ShippingStatus(Enum):
    SUCCESS = "SUCCESS"
    FAILED = "FAILED"

class Shipping(Base, SerializerMixin):
    __tablename__ = "payment_result"

    id = Column(UUIDType(binary=False), primary_key=True, default=uuid4)
    external_shipping_id = Column(UUIDType(binary=False))
    order_id = Column(String(36))
    status = Column(SQLAEnum(ShippingStatus))

    def __repr__(self):
        return f"Shipping(id={self.id})"

Base.metadata.create_all(engine)
