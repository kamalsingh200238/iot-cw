/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./*.html", "./src/**/*.{js,ts,html}"],
  theme: {
    extend: {
      borderWidth: {
        3: "3px",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
