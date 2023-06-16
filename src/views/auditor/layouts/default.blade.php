<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('css')
    @yield('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <style>
        body {
            background-color: #000000;
        }

        .bg-light-black {
            background-color: #1e1f25;
        }

        /* tabs  */
        [data-tab-info] {
            display: none;
        }

        .active[data-tab-info] {
            display: block;
        }

        .tabs {
            border-bottom: 1px solid #4c4c4c;
        }

        .tabs button svg {
            fill: #ffffff;
        }

        .tabs button {
            color: #ffffff;
        }

        .tabs button.active {
            color: #4bc1db;
        }

        .tabs button.active svg {
            fill: #4bc1db;
        }

        .tabs button.active::after {
            position: absolute;
            content: "";
            border-bottom: 1px solid #4bc1db;
            width: 100%;
            height: 1px;
            bottom: 0;
            left: 0;
        }

        /* tabs end  */

        .dropdown-toggle::after {
            right: 20px;
            top: 15px;
            position: absolute;
        }
    </style>    
</head>


<body>
    <div class="tabs flex items-center pt-3 m-3">
        <button data-tab-value="#tab_standard"
            class="active uppercase d-flex items-center me-3 text-[13px] pb-2 relative custom-action">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="15"
                viewBox="0 0 24 24" class="me-2">
                <defs>
                    <clipPath id="clip-path">
                        <rect id="Rectangle_12463" data-name="Rectangle 12463" width="24" height="24" />
                    </clipPath>
                </defs>
                <g id="noun-grid-608343" transform="translate(-70.004 2)">
                    <g id="Mask_Group_26" data-name="Mask Group 26" transform="translate(70.004 -2)"
                        clip-path="url(#clip-path)">
                        <g id="Group_5253" data-name="Group 5253" transform="translate(2.4 2.4)">
                            <path id="Path_94449" data-name="Path 94449"
                                d="M78.731.873A.872.872,0,0,0,77.859,0H70.877A.872.872,0,0,0,70,.873V7.855a.872.872,0,0,0,.873.873h6.982a.872.872,0,0,0,.873-.873Z"
                                transform="translate(-70.004)" />
                            <path id="Path_94450" data-name="Path 94450"
                                d="M376.33,8.731h6.982a.872.872,0,0,0,.873-.873V.877A.872.872,0,0,0,383.312,0H376.33a.872.872,0,0,0-.873.873V7.859a.872.872,0,0,0,.873.873Z"
                                transform="translate(-364.984 -0.004)" />
                            <path id="Path_94451" data-name="Path 94451"
                                d="M78.731,313.308v-6.982a.872.872,0,0,0-.873-.873H70.877a.872.872,0,0,0-.873.873v6.982a.872.872,0,0,0,.873.873h6.982a.872.872,0,0,0,.873-.873Z"
                                transform="translate(-70.004 -294.98)" />
                            <path id="Path_94452" data-name="Path 94452"
                                d="M375.45,313.312a.872.872,0,0,0,.873.873H383.3a.872.872,0,0,0,.873-.873V306.33a.872.872,0,0,0-.873-.873h-6.982a.872.872,0,0,0-.873.873Z"
                                transform="translate(-364.977 -294.984)" />
                        </g>
                    </g>
                </g>
            </svg>
    
            Standards
        </button>
        <button data-tab-value="#tab_constraint"
            class="uppercase d-flex items-center me-3 text-[13px] pb-2 relative custom-action">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20" class="me-2">
                <g id="noun-chain-1655550" transform="translate(-70.937 -14.141)">
                    <path id="Path_94447" data-name="Path 94447"
                        d="M96.886,211.746a4.451,4.451,0,0,0,.358-1.727,4.373,4.373,0,0,0-.147-1.159,5.475,5.475,0,0,0-.274-.758,4.179,4.179,0,0,0-.842-1.18,5.037,5.037,0,0,0-1.179-.842l-.632.632a1.467,1.467,0,0,0-.379,1.348,2.219,2.219,0,0,1,1.074,1.074,2.484,2.484,0,0,1,.189.779,2.155,2.155,0,0,1-.632,1.685l-4.505,4.423a2.174,2.174,0,0,1-3.074-3.075l3.474-3.475a5.065,5.065,0,0,1-.168-2.907c-.147.105-.274.232-.421.358l-4.442,4.444A4.367,4.367,0,0,0,84,214.484a4.319,4.319,0,0,0,1.284,3.1,4.408,4.408,0,0,0,6.211,0l4.463-4.465a3.447,3.447,0,0,0,.358-.421,4.349,4.349,0,0,0,.568-.948Z"
                        transform="translate(-13.065 -184.72)" />
                    <path id="Path_94448" data-name="Path 94448"
                        d="M277,18.517a4.319,4.319,0,0,0-1.284-3.1,4.408,4.408,0,0,0-6.211,0l-4.463,4.466a3.446,3.446,0,0,0-.358.421,4.359,4.359,0,0,0-.568.948,4.451,4.451,0,0,0-.358,1.727,4.373,4.373,0,0,0,.147,1.159,5.468,5.468,0,0,0,.274.758,4.179,4.179,0,0,0,.842,1.18,4.493,4.493,0,0,0,1.179.842l.632-.632a1.467,1.467,0,0,0,.379-1.348,2.219,2.219,0,0,1-1.074-1.074,2.484,2.484,0,0,1-.189-.779,2.154,2.154,0,0,1,.632-1.685l4.505-4.423a2.174,2.174,0,0,1,3.074,3.075l-3.495,3.5a5.065,5.065,0,0,1,.168,2.907c.147-.105.274-.232.421-.358l4.463-4.466A4.367,4.367,0,0,0,277,18.517Z"
                        transform="translate(-186.065 0)" />
                </g>
            </svg>
    
            Constraints
        </button>
    </div>

    @yield('content')
    
    @yield('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        const tabs = document.querySelectorAll("[data-tab-value]");
        const tabInfos = document.querySelectorAll("[data-tab-info]");

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                const target = document.querySelector(tab.dataset.tabValue);

                const targetValue = tab.dataset.tabValue;
                tabs.forEach((otherTab) => {
                    otherTab.classList.toggle("active", otherTab === tab);
                });

                tabInfos.forEach((tabInfo) => {
                    tabInfo.classList.toggle(
                        "active",
                        tabInfo.dataset.tabInfo === targetValue
                    );
                });
                target.classList.add("active");
            });
        });

        
    </script>
</body>

</html>

