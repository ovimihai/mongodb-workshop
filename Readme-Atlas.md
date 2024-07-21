## Setup a free Mongo Cluster

- Go to [MongoDB Atlas website](https://www.mongodb.com/cloud/atlas/lp/try2) and create an account (or signup with Google)
- Select the FREE Shared cluster
- Select a datacenter (eg. AWS) and a region (eg. Europe, Frankfurt) and click "Create Cluster"
- Input an user and password and click "Create User"
- Allow access from your IP ("Add My Current IP Address" button) or allow all with "0.0.0.0"
- Then click "Finish and Close" - this will take few minutes

- In order to connect you need to get the connection details:
    - Click on "Connect" button -> Connect your application ->  Copy the Connection URI
    - Replace the `<password>` in the mongodb+srv:// string and save it for later
    - Open Studio 3T and create a new connection from this url
