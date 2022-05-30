const Hapi = require('hapi');
const ci = require('miniprogram-ci')
// Create a server with a host and port
const server = Hapi.server({
    host: 'localhost',
    port: 8899
});

// Add the route
server.route({
    method: 'GET',
    path: '/',
    handler: function (request, h) {
        return {'code':0,'msg':'okok'};
    }
});

server.route({
    method: 'GET',
    path: '/upload',
    handler: async function (request, h) {
        const params = request.query;
        let { appid, key_path, path, version, desc } = params;
        const project = new ci.Project({
            appid: appid,
            type: 'miniProgram',
            projectPath: path,
            privateKeyPath: key_path,
            ignores: ['node_modules/**/*'],
        })
        try{
            let  uploadResult = await ci.upload({
                project,
                version: version,
                desc: desc,
                setting: {
                    es6: true,
                },
                onProgressUpdate: console.log,
            })
            return {'code':0,'msg':'上传成功，请登录微信公众平台进行提交审核'};
        }catch (e) {
            console.log(e);
        }
        return {'code':1,'msg':'上传失败，请使用开发者工具上传'};
    }
});

// Start the server
const start = async function () {

    try {
        await server.start();
    }
    catch (err) {
        console.log(err);
        process.exit(1);
    }

    console.log('Server running at:', server.info.uri);
};

start();
