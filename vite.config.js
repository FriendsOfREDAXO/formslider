import { defineConfig, loadEnv } from 'vite'
import liveReload from 'vite-plugin-live-reload'
import copy from 'rollup-plugin-copy'
import path from 'path'

export default ({ mode }) => {
  process.env = { ...process.env, ...loadEnv(mode, process.cwd()) }
  return defineConfig({
    plugins: [
      //vue(),
      liveReload([
        __dirname + '/**/*.php',
        __dirname +
          '/src/**/(*.svg|*.png|*.jpg|*.jpeg|*.webp|*.avif|*.gif|*.woff|*.woff2)',
        __dirname + '/var/cache/addons/(structure|url)/**'
      ])
    ],

    css: {
      modules: {
        scopeBehaviour: 'global'
        //generateScopedName: '[local]_[hash:base64:5]'
      }
    },
    // config
    publicDir: path.resolve(__dirname, 'dev'),
    base:
      process.env.NODE_ENV === 'development' ? '/' : process.env.VITE_DIST_DIR,
    resolve: {
      alias: [{ find: '@', replacement: path.resolve(__dirname, 'src') }]
    },

    build: {
      // output dir for production build
      outDir: path.resolve(
        __dirname,
        '.' + process.env.VITE_PUBLIC_DIR + process.env.VITE_DIST_DIR
      ),
      assetsDir: '',
      emptyOutDir: true,

      // emit manifest so PHP can find the hashed files
      manifest: false,

      // esbuild target
      // target: 'es2020',

      // our entry
      rollupOptions: {
        // cache: false,
        input: {
          formslider: path.resolve(__dirname + process.env.VITE_ENTRY_POINT),
          fsm: path.resolve(__dirname + process.env.VITE_SECONDARY_ENTRY_POINT)
        },
        output: [
          {
            entryFileNames: `[name].js`,
            chunkFileNames: `[name].js`,
            assetFileNames: `[name].[ext]`
          }
        ],
        plugins: [
          copy({
            targets: [
              {
                src: 'src/img/**/*',
                dest: 'assets/img'
              }
            ],
            hook: 'writeBundle'
          })
        ]
      }

      // minifying switch
      // minify: true,
    },

    server: {
      origin: `${process.env.VITE_DEV_SERVER}:${process.env.VITE_DEV_SERVER_PORT}`, // required to load scripts from custom host
      cors: {
        origin: '*',
        // methods: ["GET", "POST"],
        // allowedHeaders: ["Content-Type", "Authorization"],
        preflightContinue: true
      },
      // we need a strict port to match on PHP side
      // change freely, but update in your functions.php to match the same port
      strictPort: true,
      port: process.env.VITE_DEV_SERVER_PORT,

      // serve over http
      https: false

      // serve over httpS
      // to generate localhost certificate follow the link:
      // https://github.com/FiloSottile/mkcert - Windows, MacOS and Linux supported - Browsers Chrome, Chromium and Firefox (FF MacOS and Linux only)
      // installation example on Windows 10:
      // > choco install mkcert (this will install mkcert)
      // > mkcert -install (global one time install)
      // > mkcert localhost (in project folder files localhost-key.pem & localhost.pem will be created)
      // uncomment below to enable https
      //https: {
      //  key: fs.readFileSync('localhost-key.pem'),
      //  cert: fs.readFileSync('localhost.pem'),
      //},

      // hmr: {
      //   host: 'localhost'
      //   //port: 443
      // }
    }
  })
}
