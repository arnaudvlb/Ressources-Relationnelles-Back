@echo off
echo === Demarrage COMPLET (Back + Front + Mobile) ===

cd /d "%~dp0"
cd ..

start "Symfony Back" cmd /k "cd /d ..\Ressources-Relationnelles-Back && symfony serve"
start "React Front"  cmd /k "cd /d ..\Ressources-Relationnelles-Front && npm start"
start "React Native" cmd /k "cd /d ..\RessourcesRelationelleMobile && npm run android"

echo === TOUT EST LANCE ===