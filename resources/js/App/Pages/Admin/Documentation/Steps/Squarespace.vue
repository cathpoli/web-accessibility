<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your Squarespace Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <span class="marker-icon">1</span>Log in to <a href="https://www.squarespace.com/">Squarespace</a>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Add AceADA Script using <b>Code Injection</b></div>
                </div>
                <ul>
                    <li>
                        From the Dashboard page, choose the site where you want to integrate AceADA and click the <b>three-dot</b> on the upper right corner of the tile
                        <img src="../../../../../../../public/assets/images/integrations/squarespace-tile-threedot.png">
                    </li>
                    <br>
                    <li>
                        Click <b>SETTINGS</b>
                        <img src="../../../../../../../public/assets/images/integrations/squarespace-settings.png">
                    </li>
                    <br>
                    <li>
                        On the left pane panel, click <b>Developer Tools</b>
                        <img src="../../../../../../../public/assets/images/integrations/squarespace-developer-tool.png">
                    </li>
                    <br>
                    <li>
                        On the left pane panel again, click <b>Code Injection</b>
                        <img src="../../../../../../../public/assets/images/integrations/squarespace-code-injection.png">
                    </li>
                    <br>
                    <li>
                        On the <b>FOOTER</b> box, paste the generated script from the AceADA Installation page
                        <img src="../../../../../../../public/assets/images/integrations/square-space-footer.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Save</b> button
                    </li>
                </ul>
            </li>
            <li class="last-step rounded-b-lg">
                <div class="step-header">
                    <span class="marker-icon">3</span>
                    <div class="inline-block">Check the AceADA icon and features on your website</div>
                </div>
            </li>
        </ol>
    </div>
</template>

<style scoped>
    ol {
        list-style: none;
        padding: 0px;
    }
    ul {
        list-style: disc outside none;
        padding: 10px 10px 10px 60px;
    }
</style>