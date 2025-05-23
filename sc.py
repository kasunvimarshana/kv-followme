import os

# Path to the screenshots folder
screenshots_folder = 'Screenshots'

# List all PNG, JPG, and JPEG files in the folder
screenshots = [f for f in os.listdir(screenshots_folder) if f.endswith(('.png', '.jpg', '.jpeg'))]

# Generate the markdown content
markdown = "# 📸 Screenshots\nBelow are screenshots of the application:\n\n"
for screenshot in screenshots:
    # Format each screenshot into Markdown
    markdown += f"### {screenshot}\n"
    markdown += f"![{screenshot}]({screenshots_folder}/{screenshot})\n\n"

# Output the markdown content
print(markdown)
