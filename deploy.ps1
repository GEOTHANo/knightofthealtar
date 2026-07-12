$msdeploy = "C:\Program Files\IIS\Microsoft Web Deploy V3\msdeploy.exe"
$source = "c:\Users\Geoff\Desktop\KOA PROJECT\koa-attendance-system"
$server = "https://site79006.siteasp.net:8172/msdeploy.axd?site=site79006"
$user = "site79006"
$pass = "2Em?k8#MP=a7"

$args = @(
    "-verb:sync",
    "-source:iisApp=`"$source`"",
    "-dest:iisApp=site79006,computerName=`"$server`",userName=$user,password=$pass,authType=Basic",
    "-skip:objectName=dirPath,absolutePath=node_modules",
    "-skip:objectName=dirPath,absolutePath=tests",
    "-skip:objectName=dirPath,absolutePath=\.git",
    "-skip:objectName=dirPath,absolutePath=vendor\\voku",
    "-allowUntrusted"
)

Write-Host "Deploying changes..."
& $msdeploy $args
Write-Host "Exit code: $LASTEXITCODE"
