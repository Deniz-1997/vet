const ForkTsCheckerWebpackPlugin = require('fork-ts-checker-webpack-plugin');

module.exports = {
  configureWebpack: config => {

    // get a reference to the existing ForkTsCheckerWebpackPlugin
    const existingForkTsChecker = config.plugins.filter(
      p => p instanceof ForkTsCheckerWebpackPlugin,
    )[0];

    // remove the existing ForkTsCheckerWebpackPlugin
    // so that we can replace it with our modified version
    config.plugins = config.plugins.filter(
      p => !(p instanceof ForkTsCheckerWebpackPlugin),
    );

    // copy the options from the original ForkTsCheckerWebpackPlugin
    // instance and add the memoryLimit property
    const forkTsCheckerOptions = existingForkTsChecker?.options;

    if (forkTsCheckerOptions) {
      forkTsCheckerOptions.memoryLimit = 3072;
  
      config.plugins.push(new ForkTsCheckerWebpackPlugin(forkTsCheckerOptions));
    }
  },
  publicPath: process.env.NODE_ENV === 'production' ? '/' : '/',

  pages: {
    index: {
      entry: 'src/main.ts',
      template: 'public/index.html',
      filename: 'index.html',
      title: process.env.NODE_ENV === 'development' ? 'GISZP-DEV' : 'Министерство сельского хозяйства РФ',
    },
  },

  devServer: {
    proxy: 'https://msh-dev-app.fors.ru/',
    // Тестовый стенд (для сборки менять).
    // proxy: 'https://test-zerno.fors.ru/',
  },

  transpileDependencies: ['vuetify'],

  chainWebpack: (config) => {
    config.plugins.delete('prefetch-index');
  },
};
