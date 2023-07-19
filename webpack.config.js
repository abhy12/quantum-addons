const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { EsbuildPlugin } = require("esbuild-loader");

module.exports = {
   entry: {
      "advance-slider": {
         import: './widgets/advance-slider/js/advance-slider.js',
         filename: './js/advance-slider.js',
      },
   },
   module: {
      rules: [
         {
            // Match js, & ts files
            test: /\.[jt]s?$/,
            loader: 'esbuild-loader',
            options: {
               // JavaScript version to compile to
               target: 'es2015'
            }
         },
         {
            test: /\.css$/i,
            use: [
               MiniCssExtractPlugin.loader,
               "css-loader"
            ],
         },
      ],
   },
   plugins: [
      new MiniCssExtractPlugin({
         filename: './css/[name].css',
      }),
   ],
   resolve: {
      extensions: ['.js', '.css'],
   },
   output: {
      path: path.resolve(__dirname, "assets"),
      iife: false,
      clean: false,
   },
   devtool: 'source-map',
   optimization: {
      minimizer: [
         new EsbuildPlugin({
            css: true,
         }),
      ],
   },
}
