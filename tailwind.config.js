import preset from "./vendor/filament/support/tailwind.config.preset";

export default {
	presets: [preset],
	content: [
		"./app/**/*.php",
		"./resources/views/**/*.blade.php",
		"./resources/views/**/*.php",
		"./resources/views/**/*.html",
		"./resources/js/**/*.vue",
		"./resources/js/**/*.js",
		"./resources/js/**/*.ts",
		"./vendor/filament/**/*.blade.php",
	],
};
