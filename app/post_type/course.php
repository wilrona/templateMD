<?php

$post_type = tr_post_type('Course', 'Courses');
$post_type->setIcon('graduation');
$post_type->setArgument('supports', ['title', 'editor', 'thumbnail']);
$post_type->setArgument('show_in_rest', true);
$post_type->setArgument('show_in_graphql', true);
$post_type->setArgument('graphql_single_name', 'course');
$post_type->setArgument('graphql_plural_name', 'courses');
$post_type->setTitlePlaceholder('Course Title');
$post_type->setAdminOnly();

$post_type->addColumn('duration_number', true, 'Duration', function ($value){

    global $post;

    $options_time = [
        'years' => 'Year(s)',
        'month' => 'Month',
        'trimester' => 'Trimester',
        'semester' => 'Semester',
    ];

    echo $value . " " . $options_time[tr_posts_field('duration_month', $post->ID)];
});

$post_type->addColumn('attendant', true, 'Attendant', function ($value){

    $options = [
        'full_time' => 'Full Time',
        'part_time' => 'Part Time',
    ];

    echo $options[$value];
});

$post_type->addColumn('sessions', true, 'Deadline', function ($value){

    $index = 0;
    foreach ($value as $key => $val){
        $index = $key;
        break;
    }

    $session = $value[$index];

    $day = $session['day_deadline_application'];
    $month = $session['month_deadline_application'];
    $year = $session['year_deadline_application'];

    $date = $day." ".$month." ".$year;

    $passed = false;

//    if($day && $month && $year){
        $now = date('d F Y');
        $nowTime = strtotime($now);
        $currentTime = strtotime($date);
        if($nowTime > $currentTime){
            $passed = true;
        }
//    }

    if($passed){
        echo "<span class='uk-text-danger'>".$date."</span>";
    }else{
        if($date){
            echo $date;
        }
    }
});

$post_type->addColumn('updateAt', true, 'Last Modified', function ($value){

    $last = get_the_modified_date('j F, Y g:i a');

    if($value){
        $last = date('j F, Y g:i a', strtotime($value));
    }

    $author = get_the_modified_author();

    echo $last.'<br />'.$author;
});

$post_type->addColumn('createdAt', true, 'Created At', function ($value){
    global $post;

    $last = get_the_date('j F, Y g:i a');

    if($value){
        $last = date('j F, Y g:i a', strtotime($value));
    }

    $author = get_the_author();
    if(tr_posts_field('createAuthor', $post->ID)){
        $author = get_user_by('id', tr_posts_field('createAuthor', $post->ID))->display_name;
    }

    echo $last.'<br />'.$author;
});


$box = tr_meta_box('about_data')->setLabel('About information');
$box->addPostType($post_type->getId());
$box->setCallback(function (){
    global $post;

    $form = tr_form();

    echo $form->search('university')->setPostType('university')->setLabel('Choice university');

    if(!tr_posts_field('createdAt', $post->ID)){
        $form->hidden('createdAt')->setDefault(date('Y-m-d H:i:s'));
    }

    if(!tr_posts_field('createAuthor', $post->ID)){
        if($post->post_author){
            $form->hidden('createAuthor')->setDefault($post->post_author);
        }else{
            $form->hidden('createAuthor')->setDefault(get_current_user_id());
        }
    }

    $form->hidden('updateAt')->setDefault(date('Y-m-d H:i:s'));

    echo '<hr class="uk-margin-small"/>';

    $options_time_number = [
        'Number of days' => null
    ];

    $i = 1;
    while ($i <= 20){
        $options_time_number[$i] = $i;
        $i++;
    }

    $options_time = [
        'Select Month/Year' => null,
        'Year(s)' => 'years',
        'Month' => 'month',
        'Trimester' => 'trimester',
        'Semester' => 'semester',
    ];

    $options = [
        'Select Attendant' => null,
        'Full Time' => 'full_time',
        'Part Time' => 'part_time',
    ];

    $options_langage = [
        'Select Language' => null,
        'English' => 'English',
        'French' => 'French',
        'Arabic' => 'Arabic',
        'Swahili' => 'Swahili',
        'Afrikaans' => 'Afrikaans',
        'Portuguese' => 'Portuguese',
        'Chinese' => 'Chinese',
        'French & English' => 'French & English',
    ];

    $options_delivery = [
        'Select Delivery Mode' => null,
        'On Campus' => 'on_campus',
        'Online' => 'online',
        'Distance learning' => 'distance_learning',
        'Campus & Online (combined)' => 'campus_online'
    ];

    echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";
            echo "<div class=''>";
                echo $form->row(
                    $form->select('duration_number')->setLabel('Course duration time')->setLabelOption(true)->setOptions($options_time_number)->setSetting('default', 1),
                    $form->select('duration_month')->setLabel('')->setOptions($options_time)->setLabelOption(false)->setSetting('default', null)->setAttribute('class', 'uk-margin-top')
                );
            echo "</div>";
            echo "<div class=''>";
                echo $form->row(
                    $form->select('language')->setLabel('Language')->setOptions($options_langage)->setSetting('default', 'english'),
                    $form->select('attendant')->setLabel('Attendant')->setOptions($options)->setSetting('default', 'full_time')
                );
            echo "</div>";
    echo "</div>";

    echo '<hr class="uk-margin-small"/>';

    echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

    echo "<div class=''>";
    echo $form->text('final_award')->setLabel('Final Award')->setAttribute('placeholder', 'E.g Bachelor of science in mathematics');
    echo "</div>";
    echo "<div class=''>";
    echo $form->select('delivery_mode')->setLabel('Delivery Mode')->setOptions($options_delivery)->setSetting('default', 'on_campus');
    echo "</div>";

    echo "</div>";

    echo '<hr class="uk-margin-small"/>';

    echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

    echo "<div class=''>";
    echo $form->text('academic_unit')->setLabel('Academic unit')->setAttribute('placeholder', 'E.g Faculty of Science');
    echo "</div>";
    echo "<div class=''>";
    echo $form->text('campus')->setLabel('Campus')->setAttribute('placeholder', 'E.g Braamfontein Campus');
    echo "</div>";

    echo "</div>";

    echo '<hr class="uk-margin-small"/>';

    $options_code = [
        'Select code country' => null
    ];

    $code_country = code_country();
    ksort($code_country);
    foreach($code_country as $key => $data):
        $options_code[$data['name']." - (+".$data['code'].")"] = '(+'.$data['code'].')';
    endforeach;

    echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

    echo "<div class=''>";
    echo $form->text('specialisation')->setLabel('Specialisations');
    echo "</div>";
    echo "<div class=''>";
        echo $form->row(
            $form->select('internal_record_code_country')->setLabel('Phone number <small>(for internal record)</small>')->setLabelOption(true)->setOptions($options_code),
            $form->text('internal_record_phone')->setLabel('')->setLabelOption(false)->setAttribute('class', 'uk-margin-top')
        );
    echo "</div>";
    echo "</div>";

    echo '<hr class="uk-margin-small"/>';

    echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

    echo "<div class=''>";
    echo $form->text('student_quota')->setLabel('Student Quota')->setAttribute('placeholder', 'E.g 60 students');
    echo "</div>";

    echo "<div class=''>";
    echo $form->text('course_type')->setLabel('Masters or PhD Type')->setAttribute('placeholder', 'E.g Master by research');
    echo "</div>";

    echo "</div>";

    echo '<hr class="uk-margin-small"/>';

    $repeater_child = $form->repeater('emails')->setFields([
        $form->text('email')->setLabel('email')
    ])->setLabel('Emails');

    $repeater = $form->repeater('contacts')->setFields([
        $form->row(
            $form->text('name')->setLabel('Contact Name/Office'),
            $form->text('post')->setLabel('Role/Post of Contact')
        ),
        $form->row(
            $form->select('code_country')->setLabel('Phone number <small>(for premium record)</small>')->setLabelOption(true)->setOptions($options_code),
            $form->text('phone')->setLabel('Phone number of contact')->setLabelOption(false)->setAttribute('class', 'uk-margin-top')
        ),


        $repeater_child
    ])->setLabel('Contacts');

    echo $repeater;

});

$box1 = tr_meta_box('country_data')->setLabel('Informations of course');
$box1->addPostType( $post_type->getId() );
$box1->setCallback(function(){

    $form = tr_form();


    $tab1 = function () use ($form){

        $toolbar = array(
            'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,forecolor,undo,redo,aligncenter,alignjustify,alignleft,alignnone,alignright',
        );

        echo $form->wpEditor('admission')->setLabel('Admission Requirements')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH', 'tinymce' => $toolbar]);
        echo '<hr class="uk-margin-small"/>';
        echo $form->wpEditor('application')->setLabel('Application Process')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH', 'tinymce' => $toolbar]);

    };

    $tab2 = function () use ($form){

        $toolbar = array(
            'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,forecolor,undo,redo,aligncenter,alignjustify,alignleft,alignnone,alignright',
        );

        echo $form->wpEditor('career')->setLabel('Career Opportunities')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH', 'tinymce' => $toolbar]);
        echo '<hr class="uk-margin-small"/>';
        echo $form->wpEditor('curriculum')->setLabel('Curriculum')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH', 'tinymce' => $toolbar]);
    };

    $tab3 = function() use ($form){

        global $post;

        echo $form->checkbox('all_open')->setLabel('Admission open all year ?');

        $option_month = [
            'Select Month' => null
        ];

        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $option_month[$month] = $month;
        }

        $options_days = [
            'Select Days' => null
        ];

        $i = 1;
        while ($i <= 31){
            $options_days[$i] = $i;
            $i++;
        }

        $text_year =  tr_posts_field('year_begin', $post->ID) ? tr_posts_field('year_begin', $post->ID) : 'Select Date';

        $options_year = [
            $text_year => tr_posts_field('year_begin', $post->ID)
        ];

        $current_loop = tr_posts_field('year_begin', $post->ID) ? intval(tr_posts_field('year_begin', $post->ID)) : date('Y');
        $year_start = $current_loop;
        $year_end = $current_loop + 12;

        for ($y=$year_start; $y<=$year_end; $y++) {
            $options_year[$y] = $y;
        }

        $classes_begin = $form->row(
            $form->select('day_begin')->setLabel('Day Classes Begin')
                ->setOptions($options_days)->setLabelOption(false)
                ->setSetting('default', null),
            $form->select('month_begin')->setLabel('Month Classes Begin')
                ->setOptions($option_month)->setLabelOption(false)
                ->setSetting('default', null),
            $form->select('year_begin')->setLabelOption(false)
                ->setOptions($options_year)->setSetting('default', null)
        )->setTitle('Classes Begin');

        $text_year =  tr_posts_field('year_start_application', $post->ID) ? tr_posts_field('year_start_application', $post->ID) : 'Select Date';

        $options_year = [
            $text_year => tr_posts_field('year_start_application', $post->ID)
        ];

        $current_loop = tr_posts_field('year_start_application', $post->ID) ? intval(tr_posts_field('year_start_application', $post->ID)) : date('Y');
        $year_start = $current_loop;
        $year_end = $current_loop + 12;

        for ($y=$year_start; $y<=$year_end; $y++) {
            $options_year[$y] = $y;
        }

        $start_application = $form->row(
            $form->select('day_start_application')->setLabel('Day Start Application')
                ->setOptions($options_days)->setLabelOption(false)
                ->setSetting('default', null),
            $form->select('month_start_application')->setLabel('Month Start Application')
                ->setOptions($option_month)->setLabelOption(false)
                ->setSetting('default', null),
            $form->select('year_start_application')->setLabelOption(false)
                ->setOptions($options_year)->setSetting('default', null)
        )->setTitle('Applications begin');

        $text_year =  tr_posts_field('year_deadline_application', $post->ID) ? tr_posts_field('year_deadline_application', $post->ID) : 'Select Date';

        $options_year = [
            $text_year => tr_posts_field('year_deadline_application', $post->ID)
        ];

        $current_loop = tr_posts_field('year_deadline_application', $post->ID) ? intval(tr_posts_field('year_deadline_application', $post->ID)) : date('Y');
        $year_start = $current_loop;
        $year_end = $current_loop + 12;

        for ($y=$year_start; $y<=$year_end; $y++) {
            $options_year[$y] = $y;
        }

        $deadline_application = $form->row(
            $form->select('day_deadline_application')->setLabel('Day Deadline Application')
                ->setOptions($options_days)->setLabelOption(false)
                ->setSetting('default', null),
            $form->select('month_deadline_application')->setLabel('Month Deadline Application')
                ->setOptions($option_month)->setLabelOption(false)
                ->setSetting('default', null),
            $form->select('year_deadline_application')->setLabelOption(false)
                ->setOptions($options_year)->setSetting('default', null)
        )->setTitle('Application Deadline');

        $repeater = $form->repeater('sessions')->setFields([
            $form->text('name')->setLabel('Session Name'),
            $classes_begin,
            $start_application,
            $deadline_application
        ])->setLabel('Session');

        echo $repeater;

    };

    $tab4 = function () use ($form){

        $toolbar = array(
            'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,forecolor,undo,redo,aligncenter,alignjustify,alignleft,alignnone,alignright',
        );

        $options_currency = [
            'Select currency' => null
        ];

        $symboles = symbole();
        $available_symboles = available_symbole();
        $devise = devise();
        foreach($symboles as $key => $symbole):
            if(in_array($key, $available_symboles)):
                $value = $devise[$key] ? $devise[$key] : $key ;
                $options_currency[$value.' - '.$symbole] = $key;
            endif;
        endforeach;

        echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

        echo "<div class=''>";
            echo $form->row(
                $form->text('tuition_fees_national')->setLabel('Tuition fees')->setLabelOption(false)->setAttribute('placeholder', 'Tuition fees'),
                $form->select('currency_national')->setLabel('Currency')->setLabelOption(false)->setOptions($options_currency)->setSetting('default', null)
            )->setTitle('Tuition Fees for Local Students');
        echo "</div>";
        echo "<div class=''>";
            echo $form->row(
                $form->text('tuition_fees_international')->setLabel('Tuition fees')->setLabelOption(false)->setAttribute('placeholder', 'Tuition fees'),
                $form->select('currency_international')->setLabel('Currency')->setLabelOption(false)->setOptions($options_currency)->setSetting('default', null)
            )->setTitle('Tuition Fees for Foreign Students');
        echo "</div>";

        echo "</div>";

        echo '<hr class="uk-margin-small"/>';

        echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

        echo "<div class=''>";
            echo $form->text('fees_apply_to_national')->setLabel('This fee applies to which student')->setLabelOption(true)->setAttribute('placeholder', 'E.g South Africa & SADC student');
        echo "</div>";
        echo "<div class=''>";
                echo  $form->text('fees_apply_to_international')->setLabel('This fee applies to which student')->setLabelOption(true)->setAttribute('placeholder', 'E.g International Student');
        echo "</div>";

        echo "</div>";

        $options_time = [
            'Select Month/Year' => null,
            'Year(s)' => '/year',
            'Month' => '/month',
            'Trimester' => '/trimester',
            'Semester' => '/semester',
            'For the full programme' => 'for the full programme'
        ];

        echo '<hr class="uk-margin-small"/>';

        echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

        echo "<div class=''></div>";

        echo "<div class=''>";
        echo $form->select('fees_duration')->setLabel('Fees Duration')->setOptions($options_time)->setSetting('default', 'year');
        echo "</div>";

        echo "</div>";

        echo '<hr class="uk-margin-small"/>';

        echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";

        echo "<div class=''>";
        echo $form->row(
            $form->text('tuition_fees_application_national')->setLabel('Tuition fees')->setLabelOption(false)->setAttribute('placeholder', 'Tuition fees'),
            $form->select('currency_application_national')->setLabel('Currency')->setLabelOption(false)->setOptions($options_currency)->setSetting('default', null)
        )->setTitle('Application Fee for Local Students');
        echo "</div>";

        echo "<div class=''>";
        echo $form->row(
            $form->text('tuition_fees_application_international')->setLabel('Tuition fees')->setLabelOption(false)->setAttribute('placeholder', 'Tuition fees'),
            $form->select('currency_application_international')->setLabel('Currency')->setLabelOption(false)->setOptions($options_currency)->setSetting('default', null)
        )->setTitle('Application Fee for Foreign Students');
        echo "</div>";

        echo "</div>";


        echo '<hr class="uk-margin-small"/>';

        echo $form->wpEditor('tuition_fees_detail')->setLabel('Tuition fees details')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH', 'tinymce' => $toolbar]);

    };

    $tab5 = function () use ($form){

        echo $form->text('whatsapp_number')->setLabel('WhatsApp number');
    };

    $tabs = tr_tabs();


    $tabs->addTab('Admission & Application', $tab1);
    $tabs->addTab('Career & Curriculum', $tab2);
    $tabs->addTab('Important date', $tab3);
    $tabs->addTab('Fees & Funding', $tab4);
    $tabs->addTab('Premium Fields', $tab5);

    $tabs->render('box');


});
