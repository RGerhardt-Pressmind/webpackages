currentBranch=`git rev-parse --abbrev-ref HEAD`

echo "Current branch: $currentBranch"
echo "Commit last change"

version=`git rev-list --all --count`
version=$((version+1))

echo "Version: $version"

echo "1.0.$version" > system/VERSION

git add *
git commit -m "commit build $version"

git push origin $currentBranch
