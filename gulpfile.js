import path from 'path';
import fs from 'fs';
import {src, dest, watch, series} from 'gulp';
import sharp from 'sharp';
import terser from 'gulp-terser';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';

const sass = gulpSass(dartSass);

const paths = {
  scss: 'src/scss/**/*.scss',
  img: 'src/img/*',
  js: 'src/js/*.js'
}

export function css(done) {
  src(paths.scss)
    .pipe(sass({outputStyle : 'compressed'}).on('error', sass.logError))
    .pipe(dest('public/build/css'))
  done()
}

export function js(done) {
  src(paths.js)
    .pipe(terser())
    .pipe(dest('public/build/js'))
  done()
}

export async function imgWebpJpeg(done) {
  const inputFolder = './src/img';
  const outputFolder = './public/build/img';

  const images = fs.readdirSync(inputFolder);

  await Promise.all(images.map(img => {
    procesarImagenes(path.join(inputFolder, img), outputFolder);
  }))
  done();
}

async function procesarImagenes(file, outputFolder) {
  if (!fs.existsSync(outputFolder)) {
    fs.mkdirSync(outputFolder, { recursive: true })
  }
  const baseName = path.basename(file, path.extname(file))
  const extName = path.extname(file)
  const outputFileJpg = path.join(outputFolder, `${baseName}${extName}`)
  const outputFileWebp = path.join(outputFolder, `${baseName}.webp`)
  const outputFileAvif = path.join(outputFolder, `${baseName}.avif`)
  const options = {quality: 80}
  
  await sharp(file).jpeg(options).toFile(outputFileJpg)
  await sharp(file).webp(options).toFile(outputFileWebp)
  await sharp(file).avif().toFile(outputFileAvif)
}

export function dev() {
  watch(paths.scss, css);
  watch(paths.js, js);
  watch(paths.img, imgWebpJpeg);
}

export default series(imgWebpJpeg, css, js, dev);