<h1>MultiPlayerCounter<img src="assets/images/icon.png" height="64" width="64" align="left"></img></h1><br/>

[![Lint](https://poggit.pmmp.io/ci.shield/Taylor-pm-pl/MultiPlayerCounter/MultiPlayerCounter)](https://poggit.pmmp.io/ci/Taylor-pm-pl/MultiPlayerCounter/MultiPlayerCounter)
[![PHPStan](https://github.com/david-pm-pl/MultiPlayerCounter/actions/workflows/php.yml/badge.svg)](https://github.com/david-pm-pl/MultiPlayerCounter/actions/workflows/php.yml/badge.svg)
[![Discord](https://img.shields.io/discord/1100650029573738508.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/)

**NOTICE:** This plugin branch is for PocketMine-MP 5. <br/>
✨ **Combine players of multiple servers**
</div>

## Features
- Combine players of multiple servers
- Easy to set up
- API For Developers

# How to Install

1. Download the latest version
2. Place the `MultiPlayerCounter.phar` file into the `plugins` folder.
3. Restart the server.
4. Done!

# API

 ### Check server is online

  ```php
  use davidglitch04\MultiPlayerCounter\api\ServerAPI;

  $status = ServerAPI::isOnline(ip: $ip, port: $port); // Return bool
  var_dump($status);
  ```

 ### Get ServerInfo
  ```php
  use davidglitch04\MultiPlayerCounter\api\ServerAPI;

  $status = ServerAPI::getServerInfo(ip: $ip, port: $port); // Return ServerInfo
  var_dump($status);
  ```
# Credits

| Logo  | Description |
| ------------- | ----------- |
| <img src="https://resources.jetbrains.com/storage/products/company/brand/logos/PhpStorm_icon.png" height="64" width="64" align="left"> | <a href="https://jb.gg/OpenSourceSupport">IDE for this project is supported by Jetbrains</a> |

# Additional Notes

- If you found bugs or want to give suggestions, please visit <a href="https://github.com/David-pm-pl/MultiPlayerCounter/issues">here</a> or join our Discord server.
- We accept all contributions! If you want to contribute, please make a pull request in <a href="https://github.com/David-pm-pl/MultiPlayerCounter/pulls">here</a>.
 
