require 'bootstrap-sass'
require 'fileutils'

css_dir = "dist/css"
sass_dir = "sass"
images_dir = "dist/images"
javascripts_dir = "javascripts"
fonts_dir = "dist/fonts"
sprite_load_path = "sprites"
generated_images_dir = "dist/images/gen"

output_style = :compressed
relative_assets = true
line_comments = false

on_stylesheet_saved do |file|
  if File.exists?(file)
    filename = File.basename(file, File.extname(file))
    File.rename(file, css_dir + "/" + filename + ".min" + File.extname(file))
    puts "#{filename}#{File.extname(file)} renamed to #{filename}.min#{File.extname(file)}"
  end
end
