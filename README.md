<p align="center"><img src="/public/android-chrome-96x96.png"></p>
<h2 align="center">Faviconator</h2>
<p align="center">A new and easy way to generate favicons</p>
<p align="center">
    <a href="#about">About</a> •
    <a href="#features">Features</a> •
    <a href="#deploy">Deploy</a> •
    <a href="#license">License</a>
</p>

## About

I needed a simple tool to generate favicons in different formats and sizes, with related code and webmanifest generation.  
I didn't like the existing tools, either because they didn't offer what I wanted, or because they are full of ads, trackers and other privacy issues.  
That's why this feature-packed tool was created.

## Features

- ✅ Generates common **favicons** in **different formats**
- ✅ Can **include** a **48x48** and a **64x64** version in the **.ico file**
- ✅ Can **generate** **Android favicons** and **webmanifest** (with application name, language, themes, ... customizable)
- ✅ Can **generate** **old Apple favicons**
- ✅ Can **generate** **Microsoft favicons** and **tiles** and **browserconfig.xml** (with possible customisation of the tile colour)
- ✅ **Generates** the **HTML code** to be integrated in your pages, **taking into account** the **parameters** activated or not
- ✨ A **simple** and **beautiful** interface
- 🌙 An **elegant dark mode** activated according to the settings of your browser or operating system

## Deploy

### Install dependencies

First check that you have **Node.js**, **npm** and **Composer** installed on your machine.  
Then, **install PHP dependencies**:  
```bash
composer install --optimize-autoloader
```
Install **Node.js dependencies**:  
```bash
npm install
```

### Build assets
Don't forget to **generate the assets**:
```bash
npm run build
```

### Switch to production mode
If you want to put the application into **production mode**:
```bash
composer dump-env prod
```

### Clear cache
Then **clear cache**:
```bash
php bin/console cache:clear
```

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program. If not, see http://www.gnu.org/licenses/.

