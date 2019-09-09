var ORDER_AJAX_DELIVERY_MAP = {
    CITY_MIN_PRICE: 450, // минмиальаня цена по городу
    CITY_MAX_PRICE: false, // максимальаня цена по городу
    CITY_PROMO_PRICE: 450,
    CITY_PROMO_LIMIT_ORDER_COST: 15000,
    CITY_FREE_DELIVERY_PRICE: 450,
    CITY_FREE_DELIVERY_TEXT: '',
    CITY_FREE_DELIVERY_LIMIT_ORDER_COST: 15000,
    CITY_CONDITIONS: [],
    OUTSIDE_MIN_PRICE: 500, // минимальаня цена за городом
    OUTSIDE_MAX_PRICE: false, // максимальаня цена за городом
    OUTSIDE_CONDITIONS: [
        { KM_MIN: 1, KM_MAX: false, KM_PRICE: 40, FIX_PRICE: 500, FULL_PATH_CALC: true },
    ],
    MAP_CENTER: [56.355300, 37.500127],
    MAP_ZOOM: 11,
    MAP_FROM: [56.355300, 37.500127],
    MAP_POLYGON: [
        [56.35008032988424,37.50520173732511],
        [56.35041989634853,37.50544313613632],
        [56.350264502351806,37.506060230484394],
        [56.35034169501382,37.50666918506802],
        [56.349942302161075,37.50933807617521],
        [56.34670035405225,37.50698883362728],
        [56.345305179532886,37.50603231728243],
        [56.34278583628516,37.50480544390287],
        [56.33883910143067,37.503376830558956],
        [56.33310702784748,37.50105948307679],
        [56.328762952552815,37.49897502283112],
        [56.32469957301777,37.49613050643682],
        [56.3240088372272,37.49865994984299],
        [56.32322270430942,37.49962298318453],
        [56.32303511088884,37.501207528236264],
        [56.32253751190646,37.50317831138631],
        [56.322320498147896,37.504809855248524],
        [56.3225327246018,37.505540176881695],
        [56.32294675372515,37.506401987674565],
        [56.32328924000771,37.50752129053286],
        [56.32341396348562,37.50865482613532],
        [56.323431379500676,37.509788361737826],
        [56.32334876725285,37.512069448575126],
        [56.3210851049977,37.514979381496545],
        [56.32089661307927,37.51562794835863],
        [56.320888851179504,37.5164189667718],
        [56.32238346144505,37.51716706984081],
        [56.32213813194814,37.52071685684893],
        [56.322488971549305,37.521949215268165],
        [56.32252429151225,37.52357390506741],
        [56.32221383425027,37.5259496133908],
        [56.32199280061751,37.528550627271436],
        [56.32173599504868,37.53103362395546],
        [56.321839527451445,37.532083183919795],
        [56.321860745239185,37.533978455963364],
        [56.32219974806021,37.537395123267494],
        [56.32254848478807,37.53880223327033],
        [56.32250671036146,37.54052211199797],
        [56.32117221101461,37.54497690995892],
        [56.320352180535025,37.546934220114274],
        [56.321237329791636,37.55086595935233],
        [56.32153820564928,37.5519438281986],
        [56.32212802679797,37.55354166641606],
        [56.322461094746956,37.55514947511411],
        [56.3226502940984,37.55553267979043],
        [56.32379176395877,37.556084512421926],
        [56.323974390974755,37.55623190642441],
        [56.32399863904257,37.55682710183905],
        [56.323895500262516,37.55737376250478],
        [56.32349676892607,37.558381253147765],
        [56.324031900073905,37.56097559158087],
        [56.3261127805264,37.55837695171898],
        [56.327611585271036,37.55543127483807],
        [56.32818054223423,37.55467121594389],
        [56.32901175963507,37.55446905652481],
        [56.33043572410289,37.5574550498815],
        [56.33407723933935,37.55976562607615],
        [56.33464913360313,37.55840710213142],
        [56.335790286020824,37.558796189895936],
        [56.335358091276944,37.559914838512526],
        [56.33511349883701,37.56030677594343],
        [56.33557736572798,37.561064637897786],
        [56.33626670327907,37.56185932175443],
        [56.33812208054076,37.56370071593999],
        [56.33909174270332,37.565056137874755],
        [56.33984685897265,37.56658322118656],
        [56.34195660610839,37.563885002025856],
        [56.34314252123508,37.56237495990464],
        [56.34401856986625,37.56073617175073],
        [56.345127477670566,37.5589455599145],
        [56.345521052479306,37.55822191537337],
        [56.345926539202345,37.55758410152078],
        [56.346982454152375,37.557718176463496],
        [56.34735549916601,37.55780667160695],
        [56.34768087888488,37.55763767468508],
        [56.34811529666944,37.556966434153566],
        [56.349484544456,37.55631607119689],
        [56.34994129357089,37.55644153608089],
        [56.35052117000289,37.55635146323912],
        [56.351066531978056,37.556066800825064],
        [56.352380343330196,37.55491310269023],
        [56.3540885860576,37.55201547886839],
        [56.356093202770616,37.55724722770895],
        [56.35417555805637,37.56118041344098],
        [56.354495750405434,37.5619162264202],
        [56.35434536649389,37.56227116571932],
        [56.354619382829135,37.56286974218406],
        [56.3544704777856,37.56300697869821],
        [56.35452959269173,37.563241972592955],
        [56.35433365494545,37.563432280785996],
        [56.354426614870135,37.563654775487215],
        [56.35484420987601,37.56338673926521],
        [56.35511852768439,37.563590390508075],
        [56.35657177698015,37.56335919762436],
        [56.35641844469693,37.563583596390416],
        [56.356296382683674,37.56379994852994],
        [56.35627855674351,37.56397874974272],
        [56.35661841289436,37.56500146367646],
        [56.35609459929684,37.5660241776106],
        [56.35633319923282,37.566660653446085],
        [56.355302938320705,37.56880072167383],
        [56.355176672940644,37.570669399802874],
        [56.35561211886692,37.57182357896109],
        [56.35626198949682,37.573020673463766],
        [56.356973275883036,37.57198869540409],
        [56.35717480316208,37.570917293389165],
        [56.35849509161137,37.569418832971614],
        [56.35944905029935,37.56821737962681],
        [56.36034938356437,37.56675843421684],
        [56.360157596381924,37.56634417692909],
        [56.36236591274684,37.56398800031508],
        [56.36166792032445,37.56208243481548],
        [56.36131296373031,37.56107600788534],
        [56.36107711618135,37.56006958095505],
        [56.360869810779256,37.55844473831347],
        [56.36098332903498,37.55697595027163],
        [56.3610733857741,37.55542546782548],
        [56.36117012037906,37.55470768906202],
        [56.36113583178806,37.55371846526785],
        [56.36114323238618,37.552514664753026],
        [56.36122643501756,37.5514493896248],
        [56.36136901749863,37.550366523036594],
        [56.36233345506477,37.55034383386377],
        [56.36273806957641,37.549677414527395],
        [56.36257516717145,37.54780443370229],
        [56.36293633555085,37.54716526902451],
        [56.36331185353418,37.54607440902247],
        [56.36434872399149,37.54497942584291],
        [56.364540626085144,37.545508583170886],
        [56.36480532708354,37.546561379623036],
        [56.36502506538527,37.54673292595901],
        [56.36503578151885,37.54737263761071],
        [56.36515249339817,37.547604994983274],
        [56.365284682062864,37.54878658305199],
        [56.36740098881493,37.55236985653117],
        [56.368024981801284,37.552123069001446],
        [56.36777241808065,37.55083557060558],
        [56.36730548655828,37.548883797942004],
        [56.36682068517406,37.546899838769505],
        [56.36689415823219,37.54683498565423],
        [56.36766826075721,37.550010721127535],
        [56.36801957900217,37.55159322444637],
        [56.36827562247304,37.553164998929375],
        [56.36840662078806,37.55313281242106],
        [56.36985692994837,37.55261216625821],
        [56.371116655120304,37.55191985871814],
        [56.37288836487317,37.5520000273746],
        [56.37415989653288,37.55171541560493],
        [56.37414969423046,37.550340934132194],
        [56.374559371182016,37.54938539763164],
        [56.375685866983034,37.548531450028534],
        [56.37650276484112,37.548407063277864],
        [56.37775589030041,37.547091581129656],
        [56.38140037430575,37.54525961554508],
        [56.38088917678498,37.541320967841756],
        [56.38217560898759,37.54062242862918],
        [56.38224989244603,37.5387583546573],
        [56.38219608175341,37.53744087838507],
        [56.381636081749726,37.536223240436556],
        [56.38153988318294,37.534470002095574],
        [56.38349754458568,37.5332223572499],
        [56.38350286971995,37.53285447696119],
        [56.38570382524171,37.53304139628571],
        [56.386748810246516,37.533973134408654],
        [56.39040787180117,37.52930033304225],
        [56.392393488164174,37.53012814374731],
        [56.39454364331819,37.53591034833653],
        [56.396698355289374,37.53252305362696],
        [56.39751963498618,37.53107541513028],
        [56.39771021694343,37.53027150679702],
        [56.397758187678264,37.529521997015415],
        [56.398307057653476,37.52798307959269],
        [56.399075313510735,37.521892118196114],
        [56.39870122766135,37.52017852191192],
        [56.39804824192106,37.519025842588064],
        [56.396847906469546,37.518793820475906],
        [56.3957427358591,37.519677597314484],
        [56.39494657483495,37.519994847965705],
        [56.3932351915576,37.51940189521389],
        [56.39223782407339,37.52000633004085],
        [56.39131184081298,37.5208682569331],
        [56.39051187599268,37.5212224921368],
        [56.38948321057996,37.52067230943333],
        [56.387009190549506,37.51840499328629],
        [56.386393705044256,37.51702409251635],
        [56.38629303106554,37.516122739355424],
        [56.38623997014722,37.51504972481663],
        [56.38645624522948,37.5134097132645],
        [56.38627769599664,37.512597212109384],
        [56.385908691611725,37.51101223475872],
        [56.38387587535384,37.512579437446014],
        [56.381557236277075,37.5138033173798],
        [56.38050041940637,37.517151506852855],
        [56.379634059878654,37.51813935239368],
        [56.37988952092422,37.519213821245145],
        [56.37578741457859,37.52204781921085],
        [56.371781136016274,37.52464384930745],
        [56.37054133608836,37.525400145192485],
        [56.36993332937629,37.52564954710205],
        [56.36928958702847,37.52585603366773],
        [56.368514836270656,37.52599814721646],
        [56.3677519788231,37.52590422637244],
        [56.36696528731262,37.52574593251164],
        [56.366250037144525,37.52552326563485],
        [56.36758642995616,37.51863392098841],
        [56.363359971265545,37.51607972257004],
        [56.36372790609242,37.50828069898198],
        [56.36334017216446,37.50625464057985],
        [56.36313109328287,37.505773534569656],
        [56.3629332794262,37.50563744292563],
        [56.36142735400529,37.5054860514804],
        [56.35895343034646,37.49942576277126],
        [56.35919533454551,37.49850458653466],
        [56.35935440299969,37.497678594425935],
        [56.3595551617457,37.49662729675937],
        [56.36048078143543,37.494696362803914],
        [56.359489516402114,37.49343790386165],
        [56.358156125469236,37.49330278758313],
        [56.357252281646744,37.49238882486076],
        [56.357398208466975,37.49163378302348],
        [56.3572835517287,37.49144401673587],
        [56.35749052922746,37.49043885890768],
        [56.356329058197005,37.48845938865447],
        [56.355417725257496,37.489237229268575],
        [56.354196623978545,37.49151710693111],
        [56.352973465836186,37.49076005834566],
        [56.35050920750298,37.491409674423245],
        [56.34975361211771,37.4925071763156],
        [56.349399638558495,37.491715846157305],
        [56.34487922209251,37.493448297421715],
        [56.34190854983969,37.49450374666908],
        [56.3422865010267,37.495065669457716],
        [56.342303538765684,37.49610368434646],
        [56.34279046112429,37.496720592419905],
        [56.34333528539532,37.49765400115703],
        [56.34348350008885,37.4986625117467],
        [56.34398251011496,37.49913458053341],
        [56.34500583253712,37.500271837155765],
        [56.34533799678249,37.500722448270345],
        [56.34595167375033,37.50200990859775],
        [56.34640857363171,37.502261030416754],
        [56.3475446554019,37.50266235594061],
        [56.34810095443604,37.502692530792],
        [56.34864160673375,37.503010372560226],
        [56.34912714466048,37.503560225408236],
        [56.349669271695106,37.50465993110421],
        [56.35008032988424,37.50520173732511],
    ]
};