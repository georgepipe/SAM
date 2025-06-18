/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors')

module.exports = {
  content: [ "./app/views/**/*.{php,js}", "./public/dist/*.{php,js}" ],
  theme: {
    extend: {},
    colors: {
      'gray-800': '#33294A', //F1 Violet
      ...colors
    }
  },
  plugins: [],
}

// npx tailwindcss -i ./src/css/style.css -o ./public/css/stylelive.css --watch
