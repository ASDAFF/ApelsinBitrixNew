var ORDER_AJAX_DELIVERY_MAP = {
    CITY_MIN_PRICE: 500, // минмиальаня цена по городу
    CITY_MAX_PRICE: false, // максимальаня цена по городу
    CITY_PROMO_PRICE: 500,
    CITY_PROMO_LIMIT_ORDER_COST: 15000,
    CITY_FREE_DELIVERY_PRICE: 500,
    CITY_FREE_DELIVERY_TEXT: '',
    CITY_FREE_DELIVERY_LIMIT_ORDER_COST: 15000,
    CITY_CONDITIONS: [],
    OUTSIDE_MIN_PRICE: 500, // минимальаня цена за городом
    OUTSIDE_MAX_PRICE: false, // максимальаня цена за городом
    OUTSIDE_CONDITIONS: [
        { KM_MIN: 1, KM_MAX: false, KM_PRICE: 40, FIX_PRICE: 500, FULL_PATH_CALC: false },
    ],
    MAP_CENTER: [55.071721, 38.756825],
    MAP_ZOOM: 11,
    MAP_FROM: [55.071721, 38.756825],
    MAP_POLYGON: [
        [55.094976467302175,38.79944760738941],
        [55.10062598998959,38.79315915977491],
        [55.106077877229026,38.78446745288321],
        [55.107434433065094,38.78081694925563],
        [55.10748417932982,38.77996397247338],
        [55.10757137001253,38.779009037433504],
        [55.107475114492715,38.778000389581344],
        [55.107639351960024,38.77735638489295],
        [55.10768488843376,38.776583359647944],
        [55.107763659618776,38.77529480122098],
        [55.107773582593424,38.77346870289363],
        [55.10774422212928,38.771833527417414],
        [55.1078359074476,38.76969164829694],
        [55.10771173817536,38.76744636890781],
        [55.10766022510567,38.76587405353774],
        [55.10785243767775,38.7656047508606],
        [55.10779400513718,38.763649939146816],
        [55.10764023487553,38.76162859086493],
        [55.10738189948519,38.75908793134965],
        [55.107289636422955,38.75777468624926],
        [55.10732039074337,38.756718933212355],
        [55.107363446770044,38.75600650293041],
        [55.10755412304962,38.75525115730385],
        [55.10794162370733,38.75461815597562],
        [55.10831681916498,38.75394223930378],
        [55.10928862045169,38.75387786628811],
        [55.10983601702815,38.75417827369773],
        [55.11030960125685,38.754736173172915],
        [55.11103534276467,38.75671027900734],
        [55.111508912714115,38.75784753563044],
        [55.112376084637724,38.75846980812069],
        [55.11319403798783,38.758233773728136],
        [55.11386437820444,38.75765441658108],
        [55.11524192261609,38.75602363349966],
        [55.11627504961097,38.75353454353472],
        [55.11659482157186,38.74941467048793],
        [55.117750898769735,38.74529479744045],
        [55.11848880279816,38.743749845047674],
        [55.11893153866029,38.74323486091646],
        [55.12080081345735,38.739973294755465],
        [55.12178460701027,38.73799918891989],
        [55.12286675179544,38.73563884498703],
        [55.122547030260634,38.73499511482358],
        [55.121538660892384,38.731046903153945],
        [55.12086230125181,38.73188375236619],
        [55.12016133458121,38.731304395218295],
        [55.11932507747328,38.73027442695656],
        [55.11878396055893,38.72894405128564],
        [55.11846420619724,38.72769950630254],
        [55.117775495757854,38.72707723381131],
        [55.11627504957475,38.72706650497492],
        [55.115020534735095,38.72748492958192],
        [55.115032834092126,38.724673974534305],
        [55.11645338426282,38.72436283828857],
        [55.1171605656531,38.72199176551923],
        [55.11519679106338,38.721356072843],
        [55.11515165948779,38.71836003623383],
        [55.113597798164896,38.71337514621012],
        [55.11396267348102,38.713454266806075],
        [55.1140774362834,38.71168131750766],
        [55.11400780642605,38.71190127692593],
        [55.115436626800545,38.7094148783085],
        [55.11415852829008,38.70705588004952],
        [55.112658466683996,38.70845130157426],
        [55.110875174242125,38.70902026630418],
        [55.109608448585156,38.71731399299693],
        [55.1091080673112,38.7213230606615],
        [55.10271906968211,38.71928508648575],
        [55.1009760494248,38.71944230560804],
        [55.10050124370831,38.721021952420436],
        [55.10016540722181,38.72262716736729],
        [55.099406891326076,38.722957706053954],
        [55.09961823018018,38.721320530939344],
        [55.09923173258216,38.71947197512021],
        [55.09864474279241,38.71665942206432],
        [55.09793288900102,38.71503856881506],
        [55.097232422760406,38.714013565469216],
        [55.09651303427652,38.71161278864975],
        [55.09511968552777,38.70938243197844],
        [55.09314317658243,38.70715145469273],
        [55.09227794940542,38.706207627426494],
        [55.09150074457758,38.706722766711124],
        [55.09187514775392,38.708911526844155],
        [55.087991305313146,38.71273107005815],
        [55.088205868317814,38.71764036752586],
        [55.08410372192668,38.721511222619746],
        [55.08943753663016,38.73404674019332],
        [55.08531043884495,38.73529340370434],
        [55.08906041939308,38.74065994026223],
        [55.084586526833185,38.74280071960124],
        [55.083924971794296,38.740437881731665],
        [55.078536400928215,38.74768808097153],
        [55.07664364198808,38.74352279864391],
        [55.0712048342354,38.748283907917944],
        [55.06663042094895,38.73240024267692],
        [55.06065771584377,38.741488319909514],
        [55.05934611632135,38.73869383466106],
        [55.05679347221279,38.74261809472237],
        [55.05630541727906,38.74174781203334],
        [55.05394270659431,38.74105517862334],
        [55.04990327990348,38.74736074027525],
        [55.048327515080274,38.74658527012796],
        [55.047625644926136,38.748517966799184],
        [55.04855282224192,38.75025641478593],
        [55.0476319923203,38.75293900034594],
        [55.05123567236799,38.7561584042541],
        [55.054428303215055,38.76008807327939],
        [55.05737433555851,38.76513354125506],
        [55.06050003957911,38.771098968770616],
        [55.064146969911285,38.78003138509403],
        [55.06661140884934,38.79042292312154],
        [55.06752891429343,38.79631623594595],
        [55.06903745460227,38.80225246411471],
        [55.070675324020556,38.815069058025294],
        [55.072922494487855,38.832249106385525],
        [55.06764326361594,38.836547596140946],
        [55.06533818126597,38.821872287243934],
        [55.06388752259657,38.814330350202276],
        [55.060761852282766,38.81022164069977],
        [55.05736746473688,38.81178892018096],
        [55.055960575634955,38.81595692829436],
        [55.05435654757219,38.81257183582185],
        [55.05311930564009,38.82056452929475],
        [55.051611009095346,38.813729971332485],
        [55.04866396265881,38.803403321928975],
        [55.043149085865544,38.80918341000803],
        [55.039935429017056,38.81327508368622],
        [55.03896929769396,38.8161792274101],
        [55.03864394241818,38.820370831461105],
        [55.03998170974576,38.82542382385353],
        [55.042157328967704,38.829704340049666],
        [55.040298177047596,38.8332013618219],
        [55.04358179758973,38.8404088245194],
        [55.04586870405235,38.843755342691985],
        [55.048951457722424,38.84534630306027],
        [55.049317578536034,38.846052264648065],
        [55.05007965526664,38.84790728249012],
        [55.050681567536444,38.85053477652782],
        [55.05079087657381,38.852535552266474],
        [55.05282193109151,38.857506409333],
        [55.054002933925055,38.86284204682498],
        [55.053947405312016,38.86422298061778],
        [55.053232747956365,38.866105731718406],
        [55.05049756985402,38.87107681690069],
        [55.04832688076608,38.87482641380255],
        [55.05036273601453,38.87763202501844],
        [55.052341497552696,38.88099820762587],
        [55.05322383282097,38.87996556744778],
        [55.0556418214152,38.876524360786014],
        [55.0579408618025,38.872615095485656],
        [55.05967321603464,38.86823376139844],
        [55.06109391620536,38.87030464411182],
        [55.061910471020916,38.87146614929315],
        [55.062176185637206,38.87178602988743],
        [55.06246652970082,38.87202007979365],
        [55.06273070116968,38.87210124378457],
        [55.06298255545304,38.87213949243287],
        [55.06395055856584,38.8722738202375],
        [55.064779995888244,38.872424241296386],
        [55.066540412228015,38.87264461714396],
        [55.06877175094972,38.86784926213396],
        [55.07100296472576,38.86494218226962],
        [55.07108824231653,38.866248526736776],
        [55.07121045846326,38.867479769351476],
        [55.07129419650293,38.86847229536446],
        [55.071342221888216,38.868990016042616],
        [55.071316370071195,38.86946482137704],
        [55.0747755624731,38.87045767342085],
        [55.07462955501571,38.864144188107545],
        [55.07389257184556,38.8581740255495],
        [55.0728125636051,38.85241351000182],
        [55.070258438899835,38.84175078579104],
        [55.073670612383665,38.838878935392756],
        [55.07463407532653,38.84429322409917],
        [55.0775950628419,38.838642309323724],
        [55.082372207401214,38.83446206021071],
        [55.087395727753965,38.82627322336201],
        [55.08877734881197,38.818135295759504],
        [55.09182780262923,38.809048943639866],
        [55.09361115984543,38.803885902435546],
        [55.094976467302175,38.79944760738941],
    ]
};