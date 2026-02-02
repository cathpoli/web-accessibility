<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your BigCommerce Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <span class="marker-icon">1</span>
                Log in to <a href="https://www.bigcommerce.com/">BigCommerce</a>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Add AceADA Script using <b>Script Manager</b></div>
                </div>
                <ul>
                    <li>
                        From the Home page, under left pane navigation, click <b>Storefront &gt; Script Manager</b>
                        <img src="../../../../../../../public/assets/images/integrations/bigcommerece-left-pane-script-manager.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Create a Script</b> button then add the following details:
                        <ul>
                            <li>Name of Script: &lt;your preferred name&gt;</li>
                            <li>Description: &lt;your preferred description&gt;</li>
                            <li>Location on page: &lt;it can be header or footer&gt;</li>
                            <li>Select pages where script will be added: &lt;your preferred pages on where the AceADA icon can be seen&gt;</li>
                            <li>Script category <b>Targeting; Advertising</b></li>
                            <li>Select type: <b>Script</b></li>
                            <li>Script contents: &lt;paste the generated script from the AceADA installation page&gt;</li>
                        </ul>
                        <img src="../../../../../../../public/assets/images/integrations/bigcommerce-add-script.png">
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