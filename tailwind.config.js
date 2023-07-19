/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js" 
  ],
  theme: {
    extend: {},
    colors:{
      primary:'#586FBE',
      darkgrey:"#333333",
      secondary:'#298478',
    }
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

