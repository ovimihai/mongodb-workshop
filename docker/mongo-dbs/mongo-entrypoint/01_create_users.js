

for (i = 1; i <= 20; i++) {
    db_name = 'db' + i;
    user_name = 'user' + i;
    pass_name = 'pass' + i;

    db = db.getSiblingDB(db_name);

    try {
            db.createRole(
               {
                 role: "specialUser",
                 privileges: [
                   { resource : { "db" : db_name, "collection" : ""},
                       actions : [ "collStats", "dbStats", "getDatabaseVersion", "getShardVersion", "indexStats"  ]}
                 ],
                 roles: [ { role: "read", db: db_name } ]
               }
            );
        }
        catch (err) {
            print ("specialUser role already created");
        }

    db.createUser(
        {
            user: user_name,
            pwd: pass_name,
            roles: [
                {role: "dbOwner", db: db_name},
                {role: "specialUser", db: db_name}
            ]
        },
        {
            w: "majority",
            wtimeout: 5000
        }
    );
    db.createCollection("test");
}