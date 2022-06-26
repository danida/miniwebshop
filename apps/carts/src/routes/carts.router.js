const express = require("express");
const router = express.Router();
const cartsController = require("../controllers/carts.controller");

router.post("/checkout", cartsController.checkout);

module.exports = router;
