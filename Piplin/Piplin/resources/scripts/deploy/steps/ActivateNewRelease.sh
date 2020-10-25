# Activate new release

echo -e "Activate new release {{ release_path }}"

cd {{ project_path }}

# Remove the symlink if it already exists
if [ -h {{ project_path }}/current ]; then
    rm -f {{ project_path }}/current
fi

# Create the new symlink
ln -s {{ release_path }} {{ project_path }}/current
