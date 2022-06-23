from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker


# Create database engine
engine = create_engine(url='sqlite:///:memory:', echo=True)

# Create database session
Session = sessionmaker(bind=engine)
session = Session()

print("Database connection is ready")
