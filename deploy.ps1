$msdeploy = "C:\Program Files\IIS\Microsoft Web Deploy V3\msdeploy.exe"
$source = "c:\Users\Geoff\Desktop\KOA PROJECT\koa-attendance-system"
$settings = "c:\Users\Geoff\Desktop\KOA PROJECT\koa-attendance-system\knights-of-the-altar.runasp.net-WebDeploy.publishSettings"

$args = @(
    "-verb:sync",
    "-source:iisApp=`"$source`"",
    "-dest:auto,publishSettings=`"$settings`"",
    "-skip:objectName=dirPath,absolutePath=node_modules",
    "-skip:objectName=dirPath,absolutePath=tests",
    "-skip:objectName=dirPath,absolutePath=\.git",
    "-skip:objectName=dirPath,absolutePath=vendor\\voku",
    "-allowUntrusted"
)

Write-Host "Deploying via publishSettings..."
& $msdeploy $args
Write-Host "Exit code: $LASTEXITCODE"
