/** @type {import('tailwindcss').Config} */
export default {
  content: ["./*.{js,html}"],
  theme: {
    extend: {
      borderWidth: {
        3: "3px",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
