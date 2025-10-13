
<?php


// Powred  by Abdullah Alatrash Copy Rights 2025

//date_default_timezone_set("Asia/Damascus");

define("MB", 1048576);

function filterRequest($requestname)
{
    // تحقق من POST أولاً
    if(isset($_POST[$requestname])) {
        return htmlspecialchars(strip_tags($_POST[$requestname]));
    }
    // تحقق من GET
    elseif(isset($_GET[$requestname])) {
        return htmlspecialchars(strip_tags($_GET[$requestname]));
    }
    // تحقق من input stream لطلبات JSON
    elseif($requestname && file_get_contents('php://input')) {
        $input = json_decode(file_get_contents('php://input'), true);
        if(isset($input[$requestname])) {
            return htmlspecialchars(strip_tags($input[$requestname]));
        }
    }
    
    return ""; // إرجاع قيمة فارغة بدلاً من خطأ
}
//===========for geting all data===========//
function getAllData($table, $where = null, $values = null, $json=true)
{
    global $con;
    $data = array();
    if($where == null){
        $stmt = $con->prepare("SELECT  * FROM $table");
    }else{
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }
    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if($json==true){
        if ($count > 0){
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
        return $count;
    }else{
        if($count>0){
            return  array("status" => "success","data"=>$data );
        }else{
            return (array("status" => "failure"));
        }
    }
    
    
}
//========insert data===========//
function insertData($table, $data, $json = true)
{
    global $con;
    foreach ($data as $field => $v)
        $ins[] = ':' . $field;
    $ins = implode(',', $ins);
    $fields = implode(',', array_keys($data));
    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);
    foreach ($data as $f => $v) {
        $stmt->bindValue(':' . $f, $v);
    }
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
  }
    return $count;
}

//=============update data================//
function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    }
    return $count;
}
//=============delete data=============//
function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}
//================image uplopad==========//
function imageUpload($dir , $imageRequest)
{
    if(isset($_FILES[$imageRequest]))
    {
          global $msgError;
            $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
             $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
             $imagesize  = $_FILES[$imageRequest]['size'];
             $allowExt   = array("jpg", "png", "gif", "mp3", "pdf", "svg", "SVG");
             $strToArray = explode(".", $imagename);
             $ext        = end($strToArray);
             $ext        = strtolower($ext);

            if (!empty($imagename) && !in_array($ext, $allowExt)) {
                   $msgError = "EXT";
                }
            if ($imagesize > 4 * MB) {
                $msgError = "size";
                }
            if (empty($msgError)) {
                    move_uploaded_file($imagetmp, $dir."/" . $imagename);
                 return $imagename;
                } else {
                    return "fail";
                        }
    }
         else{
             return "empty";
            }

}


//=========delete file ==============//
function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "wael" ||  $_SERVER['PHP_AUTH_PW'] != "wael12345") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }

    // End 

  

}
function printFailure($message="none"){
    echo json_encode(array("status"=>"fail","message "=>$message));
}
function printSuccess($message="none"){
    echo json_encode(array("status"=>"success","message "=>$message));
}
function result($count){
    if($count>0){
        printSuccess();
    }else{
        printFailure();
    }
}

//this function for sed emaill
function sendEmail($to,$titel,$body){

    $header="from support at supoort@gamil.com\n cc: love14144mn@gmail.com";
    mail($to,$titel,$body,$header);
}


//=============get data (subsific data)==============//

function getData($table, $where = null, $values = null, $json=true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if($json == true){
    if ($count > 0){
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    }
    else{
        return $count;
    }
   
}



//  //====================Topic Notification Function===============//
 
//  require_once __DIR__ . '/vendor/autoload.php';

// function sendGCM($title, $message, $topic, $pageid, $pagename)
// {
//     $credentialsPath = __DIR__ . '/ecommerce-eb14c-48c955e74db6.json';

//     $client = new \Google\Client();
//     $client->setAuthConfig($credentialsPath);
//     $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

//     /** @var \GuzzleHttp\Client $httpClient */
//     $httpClient = $client->authorize();

//     $firebaseConfig = json_decode(file_get_contents($credentialsPath), true);
//     $projectId = $firebaseConfig['project_id'];
//     $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

//     $payload = [
//         'message' => [
//             'topic' => $topic,
//             'notification' => [
//                 'title' => $title,
//                 'body' => $message
//             ],
//             'data' => [
//                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                 'pageid' => $pageid,
//                 'pagename' => $pagename
//             ],
//             'android' => [
//                 'priority' => 'high',
//                 'notification' => [
//                     'sound' => 'default',
//                     'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
//                 ]
//             ],
//             'apns' => [
//                 'payload' => [
//                     'aps' => [
//                         'sound' => 'default'
//                     ]
//                 ]
//             ]
//         ]
//     ];
    
//     $response = $httpClient->post($url, [
//         'json' => $payload
//     ]);

//     return $response->getBody()->getContents();
// };

// //================ Insert Notification  in DB And sending Notification=============//

// function insertNotify($titel , $body , $usersid, $topic , $pageid , $pagename ){
//     global $con;
//     $stmt = $con->prepare("INSERT INTO `notification`(`notification_titel`, `notification_body`, `notification_usersid`) VALUES (? , ? , ?)");
//     $stmt->execute(array($titel , $body , $usersid));
//     sendGCM($titel   ,  $body   ,   $topic  ,   $pageid  ,   $pagename);
//     $count = $stmt->rowCount();
//     return $count;


// }


//===========================AI CHAt=============================================//
function askAI($userMessage) {
    $apiKey = "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";
    
   
    $systemPrompt =  "أنت مساعد صيدلاني ذكي يقدم معلومات دقيقة وموثوقة عن الأدوية والصحة. " .
                    "قدم إجابات موجزة ومفيدة باللغة العربية. " .
		 "استعمل في بحثك مكاتب الدواء العالمية مثل OpenFDA وغيرها للحصول على معلومات كاملة ودقيقة " .
                    "لا تقدم نصائح طبية محددة، وذكّر المستخدم باستشارة الطبيب عند الضرورة.";
    
    $payload = [
        "model" => "deepseek/deepseek-chat:free",
        "messages" => [
            [
                "role" => "system",
                "content" => $systemPrompt
            ],
            [
                "role" => "user",
                "content" => $userMessage
            ]
        ],
        "temperature" => 0.7,  // تعديل درجة الإبداعية
        "max_tokens" => 500    // تحديد الحد الأقصى للإجابة
    ];

    // إضافة معالجة الأخطاء وإعادة المحاولة
    $maxRetries = 3;
    $retryCount = 0;
    $delay = 1;
    
    while ($retryCount < $maxRetries) {
        $ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode == 200) {
            $result = json_decode($response, true);
            if (isset($result['choices'][0]['message']['content'])) {
                return $result['choices'][0]['message']['content'];
            } else {
                return "عذراً، لم أستطع فهم الإجابة من النموذج.";
            }
        } else if ($httpCode == 429 || ($httpCode >= 500 && $httpCode < 600)) {
           
            $retryCount++;
            if ($retryCount < $maxRetries) {
               
                sleep($delay);
                $delay *= 2;
                continue;
            }
        }
        
        
        $errorMessage = "حدث خطأ في الاتصال مع AI. الكود: $httpCode";
        if ($error) {
            $errorMessage .= " - $error";
        }
        return $errorMessage;
    }
}

//=====================Fuzzy Matching===================//

function fuzzyPredefinedResponse($message) {
    $predefined = [
         // شكر وتقدير
    "شكراً" => "شكرًا لك! سعدت بخدمتك، هل تحتاج إلى شيء آخر؟ 😊",
    "أشكرك" => "العفو، نحن دائمًا هنا لمساعدتك!",
    "ممنون" => "على الرحب والسعة! هل هناك شيء آخر أقدر أساعدك فيه؟",

    // استفسارات حول المنتجات
    "منتج" => "هل تبحث عن منتج معين؟ يسعدني مساعدتك في العثور عليه.",
    "منتجات" => "لدينا مجموعة واسعة من المنتجات، ما الذي تهتم به تحديداً؟",
    "هل عندكم منتجات للأطفال؟" => "    نعم، لدينا مجموعة مخصصة للأطفال",
   

    // الأسعار والتكاليف
  
    "كلفة" => "نسعد بإعلامك أن أسعارنا مدروسة وتناسب الجميع! ",
    "هل يوجد خصومات؟" => "نعم، نقدم عروضاً موسمية وخصومات مميزة! ",
    

    // الشحن والتوصيل
    "شحن" => "نقدم تجربة شحن مميزة وسريعة",
    "توصيل" => "خدمتنا للتوصيل سريعة وموثوقية.",
    "كم يستغرق التوصيل؟" => "عادةً يستغرق التوصيل من 10 إلى 20 من الدقائق عمل حسب منطقتك.",
    "هل الشحن مجاني؟" => "في بعض العروض نعم",
    "هل تشحنون خارج حلب" => "حاليًا نخدم مناطق داخل حلب فقط، وسنعلن لاحقًا عن التوسع الخارجي بإذن الله.",

    // الطلب والدفع
    "كيف أطلب؟" => "بكل سهولة! ابحث  عن المنتج ضمن الاثسام او استخدم خاصية البحث",
    "طرق الدفع" => "نوفر عدة طرق للدفع: مدى، فيزا، تحويل بنكي، والدفع عند الاستلام .",
    "هل الدفع آمن؟" => "نعم، نظامنا معتمد وآمن، ونستخدم بوابات دفع موثوقة لحماية معلوماتك.",

    // الدعم الفني وخدمة العملاء
    "دعم فني" => "فريق الدعم جاهز دائماً لمساعدتك، تستطيع التواصل معنا على الرقم 0996094461.",
    "هل عندكم دعم مباشر؟" => "نعم، يمكنك التواصل معنا عبر الشات أو الاتصال المباشر في أوقات العمل.",
    "تواصل مع خدمة العملاء" => "بكل سرور، يمكنك التواصل معنا عبر هذا الرقم: 0996094461 أو من خلال صفحة اتصل بنا.",

    // سياسة الاسترجاع والاستبدال
    "كيف أستبدل منتج؟" => "فقط تواصل معنا مع رقم الطلب والمنتج، وسنرتب لك عملية الاستبدال بسهولة.",
    "ما هي شروط الاسترجاع؟" => "يجب أن يكون المنتج غير مستخدم وفي عبوته الأصلية خلال 1 أيام من الاستلام.",

    // معلومات عامة
    "أين تقع متاجركم؟" => "متاجرنا إلكترونية بالكامل، ويمكنك الطلب من أي مكان في سوريا.",
    "متى تفتحون؟" => "متجرنا الإلكتروني يعمل من العاشرة لمنتصف الليل، وفريق الدعم متواجد من 9 صباحًا إلى 6 مساءً.",
    "هل أنتم موثوقين؟" => "بكل تأكيد! نحن جهة معتمدة ونفخر بثقة عملائنا على مدار السنوات.",

    "هل عندكم حسابات على السوشيال ميديا؟" => "نعم، تابعنا على إنستغرام وتويتر وتيك توك @ourstore للحصول على آخر العروض والمستجدات.",
        
        "ما هي استخدامات دواء اموكسيسيلين؟" => "يستخدم الأموكسيسيلين لعلاج العدوى البكتيرية مثل التهاب الحلق، التهابات الأذن، والتهاب الشعب الهوائية.",
    "هل يمكنني تناول بانادول على معدة فارغة؟" => "نعم، يمكن تناول بانادول على معدة فارغة، ولكن يُفضل تناوله بعد الطعام لتقليل تهيج المعدة.",
    "ما الفرق بين البروفين والباراسيتامول؟" => "البروفين مضاد التهاب غير ستيرويدي، أما الباراسيتامول فهو مسكن وخافض حرارة فقط، ويقل تسببه بتهيج المعدة.",
    "هل دواء فلاجيل مضاد حيوي؟" => "نعم، فلاجيل هو مضاد حيوي يستخدم لعلاج العدوى البكتيرية والطفيليات خاصة في الجهاز الهضمي.",
    "ما هو استخدام دواء زيرتك؟" => "زيرتك هو مضاد هيستامين يُستخدم لعلاج أعراض الحساسية مثل العطس والحكة وسيلان الأنف.",

    "كم مرة أستخدم قطرة العين؟" => "تعتمد الجرعة على نوع القطرة وحالة العين، يُنصح باتباع تعليمات الطبيب أو النشرة المرفقة.",
    "هل يمكن تكرار جرعة الدواء إذا نسيت؟" => "إذا نسيت جرعة، خذها فور التذكر إلا إذا اقترب موعد الجرعة التالية. لا تأخذ جرعتين معاً.",
    "متى يبدأ مفعول الدواء؟" => "يختلف حسب نوع الدواء، غالباً يبدأ المفعول خلال 30 دقيقة إلى ساعة.",

    "ما هو أفضل علاج للصداع؟" => "يعتمد على السبب، ولكن الباراسيتامول أو الإيبوبروفين غالباً ما يكونان فعالين.",
    "أعاني من ارتجاع معدي، ما الحل؟" => "تجنب الأطعمة الدهنية والحارة، ولا تستلقِ بعد الأكل، ويمكن استخدام أدوية مضادة للحموضة.",
    "ما هو علاج الإمساك؟" => "تناول الألياف، شرب الماء بكثرة، والنشاط البدني تساعد، ويمكن استخدام ملينات إذا لزم الأمر.",
    "كيف أزيد من مناعتي؟" => "اهتم بالتغذية المتوازنة، مارس الرياضة، نم جيداً، وقلل من التوتر.",
    "هل شرب الماء يساعد في التخلص من السموم؟" => "نعم، شرب الماء بانتظام يساعد الكلى على التخلص من السموم ويحافظ على صحة الجسم.",
    "ما أفضل وقت لتناول الفيتامينات؟" => "يفضل تناول الفيتامينات مع الطعام في الصباح أو حسب توصيات النشرة الطبية.",
    "ما هو خافض الحرارة المناسب للأطفال؟" => "الباراسيتامول والإيبوبروفين مناسبان للأطفال حسب العمر والوزن، ويجب الالتزام بالجرعة.",
    "طفلي يعاني من حرارة، ماذا أفعل؟" => "يجب خفض الحرارة باستخدام خافض مناسب، وتوفير الراحة والسوائل، وإذا استمرت الحالة راجع الطبيب.",
    "هل الباراسيتامول آمن للحامل؟" => "نعم، الباراسيتامول يُعتبر آمناً للحامل إذا تم استخدامه بالجرعة المناسبة وتحت إشراف الطبيب.",
    "هل يمكن استخدام كريمات موضعية أثناء الحمل؟" => "بعض الكريمات آمنة، لكن يُفضل استشارة الطبيب قبل استخدام أي منتج أثناء الحمل.",

        // أسئلة عامة عن الأدوية
        "ما هو الباراسيتامول؟" => "الباراسيتامول هو دواء يُستخدم لتخفيف الألم وخفض الحرارة.",
        "ما هي الجرعة المناسبة للأطفال؟" => "تعتمد الجرعة على وزن الطفل، يُنصح باستشارة الطبيب.",
        "هل المضاد الحيوي يعالج الفيروسات؟" => "لا، المضادات الحيوية تعالج البكتيريا فقط وليس الفيروسات.",
        "ما هو الفرق بين بانادول وأدول؟" => "كلاهما يحتوي على نفس المادة الفعالة (باراسيتامول).",
        
        // أسئلة عن الأعراض
        "ما هي أعراض الإنفلونزا؟" => "أعراض الإنفلونزا تشمل: ارتفاع في درجة الحرارة، صداع، آلام في العضلات، سعال، تعب عام، وسيلان الأنف.",
        "ما هي أعراض كورونا؟" => "أعراض كورونا تشمل: حمى، سعال جاف، تعب، فقدان حاسة التذوق أو الشم، ضيق في التنفس، آلام في الجسم.",
        "ما هي أعراض التسمم الغذائي؟" => "أعراض التسمم الغذائي تشمل: غثيان، قيء، إسهال، آلام في البطن، وأحياناً حمى.",
        
        // أسئلة عن الأدوية الشائعة
        "ما هو الأسبرين؟" => "الأسبرين هو دواء مضاد للالتهاب ومسكن للألم ومضاد للحمى، يستخدم أيضاً كمميع للدم.",
        "ما هو الإيبوبروفين؟" => "الإيبوبروفين هو دواء مضاد للالتهاب ومسكن للألم ومضاد للحمى، مثل الأدفيل والبروفين.",
        "ما هو الديكلوفيناك؟" => "الديكلوفيناك هو دواء مضاد للالتهاب ومسكن للألم، يستخدم لعلاج الالتهابات وآلام المفاصل.",
        
        // أسئلة عن الحساسية
        "ما هي أعراض الحساسية؟" => "أعراض الحساسية تشمل: العطس، سيلان الأنف، احمرار وحكة في العينين، طفح جلدي، وأحياناً صعوبة في التنفس.",
        "ما هي أدوية الحساسية؟" => "تشمل أدوية الحساسية: مضادات الهيستامين، الستيرويدات الموضعية، ومزيلات الاحتقان.",
        
        // أسئلة عن الضغط والسكري
        "كيف أقيس ضغط الدم؟" => "يمكن قياس ضغط الدم باستخدام جهاز قياس الضغط الإلكتروني أو التقليدي، ويفضل القياس بعد الراحة لمدة 5 دقائق.",
        "ما هي المستويات الطبيعية للسكر في الدم؟" => "المستوى الطبيعي للسكر الصائم هو أقل من 100 ملغ/ديسيلتر، وبعد الأكل بساعتين أقل من 140 ملغ/ديسيلتر.",
        
        // أسئلة عن الفيتامينات
        "ما هي فوائد فيتامين د؟" => "فيتامين د ضروري لصحة العظام والأسنان، ويساعد في امتصاص الكالسيوم، ويدعم جهاز المناعة.",
        "ما هي مصادر فيتامين سي؟" => "مصادر فيتامين سي تشمل: الحمضيات، الفلفل، الفراولة، الكيوي، البروكلي، والطماطم.",
        
        // أسئلة عن الحمل والرضاعة
        "ما هي الأدوية الآمنة أثناء الحمل؟" => "يجب استشارة الطبيب قبل تناول أي دواء أثناء الحمل. بعض الأدوية الآمنة نسبياً تشمل الباراسيتامول بجرعات محددة.",
        "هل يمكن تناول المضادات الحيوية أثناء الرضاعة؟" => "بعض المضادات الحيوية آمنة أثناء الرضاعة، لكن يجب استشارة الطبيب دائماً قبل تناول أي دواء.",
        
        // أسئلة عن الصحة العامة
        "كيف أحافظ على صحة القلب؟" => "للحفاظ على صحة القلب: مارس الرياضة بانتظام، تناول غذاء صحي، تجنب التدخين، حافظ على وزن صحي، وراقب ضغط الدم والكوليسترول.",
        "ما هي طرق تقوية المناعة؟" => "لتقوية المناعة: تناول غذاء متوازن، مارس الرياضة، احصل على قسط كافٍ من النوم، قلل التوتر، وتناول الفيتامينات والمعادن الضرورية."
    ];

    $message = trim(strtolower($message));
    $bestMatch = null;
    $bestScore = 0;

    foreach ($predefined as $question => $answer) {
        similar_text($message, strtolower($question), $percent);
        if ($percent > $bestScore) {
            $bestScore = $percent;
            $bestMatch = $answer;
        }
    }

    if ($bestScore >= 80) {
        return $bestMatch;
    }

    return null; 
}









// function sendGCM($title, $message, $topic, $pageid, $pagename)
// {


//     $url = 'https://fcm.googleapis.com/fcm/send';

//     $fields = array(
//         "to" => '/topics/' . $topic,
//         'priority' => 'high',
//         'content_available' => true,

//         'notification' => array(
//             "body" =>  $message,
//             "title" =>  $title,
//             "click_action" => "FLUTTER_NOTIFICATION_CLICK",
//             "sound" => "default"

//         ),
//         'data' => array(
//             "pageid" => $pageid,
//             "pagename" => $pagename
//         )

//     );


//     $fields = json_encode($fields);
//     $headers = array(
//         'Authorization: key=' . "",
//         'Content-Type: application/json'
//     );

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

//     $result = curl_exec($ch);
//     return $result;
//     curl_close($ch);
// }

