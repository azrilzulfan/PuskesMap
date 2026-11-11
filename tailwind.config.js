/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#007979',
        'primary-light': '#42A08C',
        'primary-dark': '#407F7C',
        secondary: '#FFE2AF',
        tertiary: '#E37434',
        mint: '#E8F4F2',
        sky: '#BEE9F0',
        green: '#D9EBD4',
        cream: '#F5E6C6',
        surface: '#FFFFFF',
        canvas: '#F8FAFC',
        text: '#1F2937',
        muted: '#6B7280',
        border: '#D4E9E0',
      }
    },
  },
  plugins: [],
}