const express = require("express");
const bodyParser = require("body-parser");
const app = express();
const port = process.env.PORT || 3000;
const cartsRouter = require("./src/routes/carts.router");

app.use(bodyParser.json());
app.use(
  bodyParser.urlencoded({
    extended: true,
  })
);

app.use("/", cartsRouter);

app.listen(port, "0.0.0.0", () => {
  console.log(`App listening at port ${port}`);
});
