@echo off
echo === Demarrage WEB (Back Symfony + Front React) ===

REM On remonte du dossier script -> RessourcesRelationelleMobile -> dossier parent contenant les 3 repos
cd /d "%~dp0"
cd ..

start "Symfony Back" cmd /k "cd /d ..\Ressources-Relationnelles-Back && symfony serve"
start "React Front"  cmd /k "cd /d ..\Ressources-Relationnelles-Front && npm start"

echo === WEB lance ===