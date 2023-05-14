/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["*.{html,js,php}", "assets/php/*.php"],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}

