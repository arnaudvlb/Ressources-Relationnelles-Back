@echo off
echo === Demarrage MOBILE (Back Symfony + React Native) ===

cd /d "%~dp0"
cd ..

start "Symfony Back" cmd /k "cd /d ..\Ressources-Relationnelles-Back && symfony serve"
start "React Native" cmd /k "cd /d ..\RessourcesRelationelleMobile && npm run android"

echo === MOBILE lance ===