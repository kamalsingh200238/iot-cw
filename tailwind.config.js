/** @type {import('tailwindcss').Config} */
export default {
  content: ["./*.{js,html,php}"],
  theme: {
    extend: {
      borderWidth: {
        3: "3px",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
