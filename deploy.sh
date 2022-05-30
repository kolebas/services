#!/usr/bin/env sh

# остановить публикацию при ошибках
#set -e

# сборка
#npm run build

# переход в каталог сборки
#cd dist

# если вы публикуете на пользовательский домен
# echo 'www.example.com' > CNAME

#git init
#git add -A
#git commit -m 'deploy'

# если вы публикуете по адресу https://<USERNAME>.github.io
# git push -f git@github.com:<USERNAME>/<USERNAME>.github.io.git master

# если вы публикуете по адресу https://<USERNAME>.github.io/<REPO>
#git push -f https://kolebas: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@github.com/kolebas/services.git

#cd -

cd /home/bitrix/www/ahstep/services
mkdir "src"
cd ./src
git clone https://kolebas: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@github.com/kolebas/services.git
cd services
npm install
npm run build
rm -R /home/bitrix/www/ahstep/services/js
rm -R /home/bitrix/www/ahstep/services/css
rm -R /home/bitrix/www/ahstep/services/app.php
cp -R /home/bitrix/www/ahstep/services/build/js /home/bitrix/www/ahstep/services/js
cp -R /home/bitrix/www/ahstep/services/build/css /home/bitrix/www/ahstep/services/css
cp -R /home/bitrix/www/ahstep/services/build/app.php /home/bitrix/www/ahstep/services/app.php
rm -R /home/bitrix/www/ahstep/services/build