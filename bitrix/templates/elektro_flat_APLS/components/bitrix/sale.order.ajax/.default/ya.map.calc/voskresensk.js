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
    MAP_CENTER: [55.312773, 38.629722],
    MAP_ZOOM: 11,
    MAP_FROM: [55.312773, 38.629722],
    MAP_POLYGON: [
        [55.285395707105536,38.744955017411485],
        [55.286179979477495,38.744192097271785],
        [55.28703541710369,38.7432949767813],
        [55.29088121797408,38.74162945316252],
        [55.290808457473304,38.74228135813228],
        [55.29095681466373,38.74289852256444],
        [55.291400464748634,38.74413285142908],
        [55.29194490294979,38.74539987951828],
        [55.29352352390362,38.746732306059194],
        [55.2935163274548,38.74762259261954],
        [55.29400393990096,38.749404429289356],
        [55.295934139857586,38.74930038800657],
        [55.29571977781093,38.74652019606561],
        [55.29486874818831,38.74455539566515],
        [55.295272582130835,38.74362986896113],
        [55.295247683903696,38.739976371094286],
        [55.300878500841364,38.73850586217753],
        [55.299987959004376,38.73630416830164],
        [55.30222108342834,38.73459424770308],
        [55.302672022595324,38.73403964146559],
        [55.305287586314655,38.72995469783543],
        [55.3065526129653,38.729762010521206],
        [55.306928372944896,38.73032409551986],
        [55.30802259266095,38.72976713945585],
        [55.30839465863374,38.73087246959754],
        [55.30797361379825,38.73118098864109],
        [55.30745464633585,38.73204740716027],
        [55.30820860569211,38.732720706629685],
        [55.30902619708866,38.73196301508645],
        [55.311801969805394,38.736011842097916],
        [55.31270264315812,38.736107589665785],
        [55.31034292564225,38.72657449698373],
        [55.309735611208204,38.72753159007212],
        [55.30940218506568,38.726257610842715],
        [55.30981237561761,38.72505058500632],
        [55.309145521781524,38.72318967605603],
        [55.3111712473371,38.71849635438612],
        [55.313252480356006,38.72110436980746],
        [55.31519899267573,38.72062972009522],
        [55.31694962009709,38.717580149728256],
        [55.321292738863185,38.72309453821606],
        [55.3288650625312,38.721570810248984],
        [55.34208934933334,38.7318679178528],
        [55.344013174216066,38.7139339498444],
        [55.34169752360536,38.71261187597654],
        [55.34355190752179,38.70415012928093],
        [55.34667789757854,38.70203985353267],
        [55.34269832535542,38.68756426576651],
        [55.34102437636078,38.68247539814184],
        [55.34026461902462,38.68315802033576],
        [55.337205568744395,38.682081113415805],
        [55.33637751302011,38.6802255339082],
        [55.335109118806294,38.678970769220086],
        [55.33233521215031,38.67781481555505],
        [55.332365027829844,38.6730567336025],
        [55.32448373546413,38.67257098466894],
        [55.32445620785949,38.67152733625925],
        [55.32759958784163,38.66299441675639],
        [55.32714044517796,38.662010980840286],
        [55.32800252796533,38.659139269776496],
        [55.32669314869613,38.657254611631195],
        [55.32761660010433,38.65446692275625],
        [55.32736562073484,38.65373917040444],
        [55.32934665139653,38.64816740965434],
        [55.32887792412649,38.64748742546254],
        [55.32834802480943,38.648373851335606],
        [55.32695789531124,38.64649889882043],
        [55.32633072009631,38.64564577952631],
        [55.323863472821586,38.64362907967535],
        [55.32440588008949,38.639230578218246],
        [55.320706813200815,38.63571216264693],
        [55.32009912776474,38.64056288185758],
        [55.317220276680594,38.6400437917923],
        [55.31505099286176,38.640082601203055],
        [55.31030818604781,38.64625419890546],
        [55.308914361246075,38.64305423513755],
        [55.30397733185228,38.64550565668785],
        [55.30420408949232,38.649616126872395],
        [55.30285829680712,38.650086810347034],
        [55.30282937896672,38.65010971100357],
        [55.30290202978419,38.65012259534723],
        [55.30255847845558,38.6563152892621],
        [55.302728263468424,38.66097591512802],
        [55.30272507782329,38.66102745250551],
        [55.30273581297933,38.66101543826474],
        [55.30275878937241,38.66104633936783],
        [55.30280139425195,38.66100045358816],
        [55.30638570502234,38.65850984935392],
        [55.30998192951558,38.65614799115154],
        [55.31129930914728,38.659964428252884],
        [55.309014540653095,38.66199066399971],
        [55.30920204414193,38.66182821719091],
        [55.31126209734988,38.66756663021414],
        [55.314447919787405,38.663820752160575],
        [55.315115581223985,38.66513901368471],
        [55.31630885094077,38.66316616237295],
        [55.31649397081499,38.663638544790054],
        [55.3166790898331,38.663982181174404],
        [55.317079917269204,38.664390504206224],
        [55.317722484422326,38.66496575145782],
        [55.318401750313896,38.665626829397745],
        [55.317735423096345,38.66793854724125],
        [55.31715293170846,38.66942431787153],
        [55.316497009923154,38.670609681091385],
        [55.316400924411035,38.67090455091914],
        [55.31634766829253,38.67122087841903],
        [55.31630596198952,38.671504499919536],
        [55.316321819942196,38.6716677683417],
        [55.31636215195161,38.67178812141959],
        [55.31664197722503,38.67164051150994],
        [55.31673662212964,38.67188709791179],
        [55.31666463462611,38.672233014461234],
        [55.316519225230955,38.67257893101072],
        [55.31612438964567,38.673399510140655],
        [55.31586812679985,38.67354564632587],
        [55.31559350641584,38.67358449415055],
        [55.315264987188776,38.67355966161381],
        [55.31516285766787,38.673636753019515],
        [55.315079669584975,38.67357471588436],
        [55.31499036261506,38.67355559409349],
        [55.31498307273407,38.67341006215115],
        [55.315531638882554,38.672837064198106],
        [55.315808978396966,38.67253983638586],
        [55.31594458813288,38.672377811434814],
        [55.316067960303144,38.672172871139274],
        [55.3160745508904,38.672029870345625],
        [55.316050548703515,38.67188686955219],
        [55.31593476794156,38.67177873747521],
        [55.31580674965914,38.67182080910376],
        [55.31534056238421,38.67243434231231],
        [55.31487131023906,38.673080062029086],
        [55.313990918605505,38.67393161918439],
        [55.31307685274216,38.67407507315957],
        [55.312152680079855,38.67389951491731],
        [55.310787986711176,38.673692074219574],
        [55.309447724519146,38.673602650719204],
        [55.30591656747479,38.674063990670135],
        [55.303346029282785,38.67448241527721],
        [55.30291452969001,38.67459506805557],
        [55.30266205433488,38.675005446034355],
        [55.302262680944565,38.67507250126017],
        [55.301769961831944,38.67428929622734],
        [55.3015159489593,38.674300025064085],
        [55.30040806791288,38.67425212564006],
        [55.299655179056806,38.6742170667533],
        [55.298914518293266,38.674289296228544],
        [55.29857096360463,38.67454142387502],
        [55.29823964858111,38.67490083988345],
        [55.297485188752724,38.67590935047297],
        [55.29703449541966,38.676746199685496],
        [55.29668174205242,38.67760450656982],
        [55.296447591655976,38.67917091663493],
        [55.2960244339851,38.68286700065756],
        [55.29440751673395,38.68978173549839],
        [55.29340371814774,38.69318811033484],
        [55.29239307725861,38.6954143591234],
        [55.29167628377263,38.69770498092799],
        [55.290738789882674,38.701583455163366],
        [55.29015389725563,38.703948853078295],
        [55.28954450532299,38.70609967427289],
        [55.28931146071599,38.70811607458102],
        [55.289188241298255,38.71111890693326],
        [55.28920543752731,38.71217930887028],
        [55.28902670634169,38.71375469493816],
        [55.289037779904255,38.714514689466235],
        [55.28890249331256,38.71510926345135],
        [55.28871822393999,38.71630465225594],
        [55.28834238779088,38.717905107582055],
        [55.28829718390296,38.71866871369419],
        [55.28798635188596,38.71949318715874],
        [55.28738161443611,38.72036057596747],
        [55.286293140210375,38.72256370486546],
        [55.285565193777686,38.72324684470763],
        [55.28479437014566,38.72406945941959],
        [55.28435622748766,38.72442184858823],
        [55.28315263025441,38.724666949395534],
        [55.28216333031259,38.724488261179026],
        [55.281137261817214,38.72423447111031],
        [55.27881483497005,38.72293567252563],
        [55.276175267441275,38.721127187803525],
        [55.27470498598119,38.72039060011077],
        [55.273755446378125,38.719947204412726],
        [55.27287938702848,38.71971838543591],
        [55.27096752274788,38.71929380634948],
        [55.26998752991389,38.719275178313744],
        [55.26889706162135,38.719555053551105],
        [55.2680883598539,38.720156793869464],
        [55.26702846998557,38.72134862017012],
        [55.26618909859528,38.72262627716141],
        [55.26302749214328,38.72653527548377],
        [55.25979569677732,38.730009417438836],
        [55.25693542395425,38.73397120940653],
        [55.2545778195969,38.73645589464885],
        [55.25423427620082,38.73667772755516],
        [55.25376236723578,38.73693031795268],
        [55.25337012023789,38.737354569726556],
        [55.252695925450055,38.73759152961927],
        [55.25160502004684,38.73806406115396],
        [55.25096760672373,38.73898720380579],
        [55.25046503502297,38.74003886111381],
        [55.25020760810516,38.74242089409423],
        [55.25015602122788,38.74784387901611],
        [55.24934056706952,38.74869349387689],
        [55.24921154047296,38.75035850027916],
        [55.24544756884131,38.75068443898516],
        [55.241808388009446,38.75295789735579],
        [55.23782555615055,38.75325724988942],
        [55.23441235867715,38.7579083909819],
        [55.23548704238281,38.76281702413868],
        [55.23954905664428,38.760274671311784],
        [55.24036521042188,38.76437384932662],
        [55.23934920527502,38.76501910476976],
        [55.24008812164319,38.76990377573375],
        [55.240753461597905,38.76998192814467],
        [55.24122875616884,38.769716757800815],
        [55.24152382244559,38.770168842310326],
        [55.24049477158057,38.77106080909973],
        [55.240026213666944,38.771453148313235],
        [55.23976741113034,38.771756606281436],
        [55.23975995320663,38.77222099678991],
        [55.239775494665636,38.77282770519028],
        [55.24058491149307,38.77347196451793],
        [55.24105715346657,38.7744059024201],
        [55.24224375598711,38.77500241114864],
        [55.24316674025046,38.77545944500961],
        [55.243309690005006,38.77586015248103],
        [55.24336682187409,38.776389605985386],
        [55.24355464291439,38.77705154606058],
        [55.243924152577705,38.77711478797201],
        [55.247051916906685,38.774946431982094],
        [55.24848690112359,38.7750491959228],
        [55.24887054143891,38.7741614787581],
        [55.25002028409254,38.77419644149601],
        [55.25114299255951,38.775704031003684],
        [55.25228041231747,38.781682768917506],
        [55.25339328633888,38.78199668139143],
        [55.25462380572338,38.7813811614334],
        [55.2545649674255,38.780064890864544],
        [55.254469360461925,38.77892028167227],
        [55.25382954675962,38.77910544811545],
        [55.253711606054146,38.7770891790985],
        [55.25417433838286,38.776747360668786],
        [55.254205088322216,38.77595643544867],
        [55.255130536014335,38.77576632504788],
        [55.255810847136395,38.776219944810045],
        [55.25624001569538,38.7742733139447],
        [55.255057436828366,38.77360040592164],
        [55.25676722364139,38.77194044498033],
        [55.25798137204938,38.77106329193753],
        [55.25833764235931,38.77263231351843],
        [55.258657144768414,38.77377218165487],
        [55.259212106068155,38.77309598220761],
        [55.25966026285959,38.7713788028857],
        [55.25953944001472,38.77002583843658],
        [55.26015561324396,38.76972316881642],
        [55.260542391926776,38.76855993010182],
        [55.260647319240256,38.76720357233948],
        [55.26242147029716,38.7675982770631],
        [55.26240653598356,38.76863671194946],
        [55.263761316936304,38.76886822192377],
        [55.26379271591487,38.769271393274565],
        [55.26390605826576,38.773308795624715],
        [55.264109768169575,38.77588539627464],
        [55.26470174698713,38.77721661194446],
        [55.26539173961289,38.775629584204765],
        [55.26485261074665,38.7732330449111],
        [55.26529371447025,38.770836505617766],
        [55.26496901021811,38.76728797201262],
        [55.26447276024496,38.76577791725833],
        [55.26273277164939,38.76177617709637],
        [55.26562378478812,38.75854041056929],
        [55.26600272007316,38.76141058651879],
        [55.2663318958065,38.76440865218297],
        [55.267249180774726,38.766548410962415],
        [55.267994916691364,38.7697610533478],
        [55.26844659475287,38.77305952642168],
        [55.27211869796359,38.77051550424825],
        [55.27068204898773,38.76513906935283],
        [55.27383944264494,38.76239212240629],
        [55.27640861719534,38.7689148898153],
        [55.277507764543785,38.76839954076917],
        [55.2771263999329,38.765137792149446],
        [55.27880020866502,38.76136110501318],
        [55.27841090127453,38.75325005933776],
        [55.27931290264135,38.75306898029972],
        [55.28006790447241,38.752115425065384],
        [55.28285161577254,38.74900668495804],
        [55.28293200370995,38.74746697726533],
        [55.28466040659443,38.74481671532229],
        [55.285395707105536,38.744955017411485],
    ]
};