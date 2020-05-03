const VueLoaderPlugin = require('vue-loader/lib/plugin')
const VueLoader = require('vue-loader');

module.exports = {
    mode: 'development',
    module:{
        rules:[
            {
                test: /\.vue$/,
                use: [
                'vue-loader'
                ]
            },
            {
                test: /\.js$/,
                loader: 'babel-loader'
              },
           
              {
                test: /\.(png|jpg|gif|svg|css|eot|ttf)$/,
                loader: 'url-loader',
              }
        ]
    },
    plugins:[
        new VueLoader.VueLoaderPlugin()
    ]
  };