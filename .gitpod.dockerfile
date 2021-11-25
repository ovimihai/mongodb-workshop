FROM gitpod/workspace-mongodb


CMD ["mkdir -p /workspace/data && mongod --dbpath /workspace/data"]
