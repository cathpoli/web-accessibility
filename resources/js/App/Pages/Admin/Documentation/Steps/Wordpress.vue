<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your Wordpress Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <span class="marker-icon">1</span>Log in to <a href="https://wordpress.com/">Wordpress</a>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Install <b>Code Snippets</b> (If you have already installed it, you may proceed to the next step)</div>
                </div>
                <ul>
                    <li>
                        Under left pane navigation, click <b>Plugins &gt; Add New</b>
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-left-pane-plugins.png">
                    </li>
                    <br>
                    <li>
                        On the search bar, enter <b>WPCode</b> and click <b>WPCode - Insert Headers and Footers + Custom Code</b> 
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-code-snippet.png">
                    </li>
                    <br>
                    <li>
                        Click <b>Install and Activate</b> 
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-install-activate.png">
                    </li>
                </ul>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">3</span>
                    <div class="inline-block">Add AceADA Code using Code Snippets</div>
                </div>
                <ul>
                    <li>
                        Under left pane navigation, click <b>Code Snippets &gt; Header & Footer</b>
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-left-pane-code-snippets.png">
                    </li>
                    <br>
                    <li>
                        Paste the generated script from the AceADA installation page into the <b>Footer</b> box
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-code-snippet-footer.png">
                    </li>
                    <br>
                    <li>Click the <b>Save Changes</b> button</li>
                </ul>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">4</span>
                    <div class="inline-block">Clear cache</div>
                </div>
                <ul>
                    <li>
                        Under left pane navigation, click <b>Settings &gt; Hosting Configuration</b>
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-hosting-configuration.png">
                    </li>
                    <br>
                    <li>
                        Scroll down to the bottom and click the <b>Clear Cache</b> button
                        <img src="../../../../../../../public/assets/images/integrations/wordpress-clear-cache.png">
                    </li>
                </ul>
            </li>
            <li class="last-step rounded-b-lg">
                <div class="step-header">
                    <span class="marker-icon">5</span>
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