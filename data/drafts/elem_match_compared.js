db.shapes.drop()
db.shapes.insert({ shape: [ {type:"square", color: "yellow", border:"dotted"},  {type:"circle", color: "blue", border:"solid"},    {type:"triangle", color: "red", border:"dotted"} ] })
db.shapes.insert({ shape: [ {type:"triangle", color: "yellow", border:"solid"}, {type:"circle", color: "green", border:"dotted"},  {type:"square", color: "red", border:"dashed"} ] })
db.shapes.insert({ shape: [ {type:"circle", color: "green", border:"dotted"},   {type:"square", color: "yellow", border:"dashed"}, {type:"circle", color: "blue", border:"solid"} ] })
db.shapes.insert({ shape: [ {type:"circle", color: "green", border:"dashed"},   {type:"square", color: "blue", border:"dotted"},   {type:"triangle", color: "red", border:"dotted"} ] })

// returns 3
db.shapes.find({ "shape.type":"circle", "shape.color": "blue" })

// retuns none
db.shapes.find({ "shape": {type: "circle", color: "blue" }})

// retuns 2
db.shapes.find({ "shape": {$elemMatch: {type: "circle", color: "blue" }}})

// Other case, retuns 3
db.shapes.find({ "shape.type": {$all: ["square", "triangle"]}})

