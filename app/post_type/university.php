<?php

$post_type = tr_post_type('University', 'Universities');
$post_type->setIcon('bank');
$post_type->setArgument('supports', ['title', 'editor', 'thumbnail']);
$post_type->setArgument('show_in_rest', true);
$post_type->setArgument('show_in_graphql', true);
$post_type->setArgument('graphql_single_name', 'university');
$post_type->setArgument('graphql_plural_name', 'universities');
$post_type->setTitlePlaceholder('School Name');
$post_type->setAdminOnly();

$post_type->addColumn('contacts', true, 'No of Contacts', function ($value){
    $counter = count($value);
    echo $counter;
});

$post_type->addColumn('courses', true, 'No of Courses', function ($value){
    global $post;

    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'university',
                'value' => $post->ID,
                'compare' => '='
            )
        )
    );

    $courses = new WP_Query($args);
    $counter = $courses->post_count;

    echo $counter;

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

$box1 = tr_meta_box('university_data')->setLabel('Informations of university');
$box1->addPostType( $post_type->getId() );
$box1->setCallback(function(){

    $form = tr_form();

    $about = function() use ($form) {

        global $post;

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

        $options = [
            'Select owership' => '0',
            'Public Institution' => 'public_inst',
            'Private Institution' => 'private_inst',
        ];

        echo "<div class='uk-grid-collapse uk-child-width-1-2' uk-grid>";
            echo "<div class=''>";
            echo $form->select('ownership')->setLabel('Ownership')->setOptions($options);
            echo "</div>";

            echo "<div class=''>";
            echo $form->text('number_student')
                ->setLabel('Number of student')
                ->setType('number');
            echo "</div>";
        echo "</div>";

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
            echo $form->text('address')->setLabel('Address of university')->setAttribute('placeholder', 'Street name, District, City');
            echo "</div>";

            echo "<div class=''>";
            echo $form->text('ranking')->setLabel('Ranking');
            echo "</div>";
        echo "</div>";

        $option_leader = [
            'Select' => '0',
            'Vice-Chancellor' => 'Vice-Chancellor',
            'President' => 'President',
            'Director' => 'Director',
            'Founder' => 'Founder',
            'Provost' => 'Provost',
            'CEO' => 'CEO',
            'Principal' => 'Principal',
            'Rector' => 'Rector',
            'Chancellor' => 'Chancellor'
        ];

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
                echo $form->row(
                    $form->select('title_leader')->setLabel('LeaderShip')->setOptions($option_leader),
                    $form->text('name_leader')->setLabel('')->setAttribute('placeholder', 'Enter the name')
                );
                echo "</div>";

                echo "<div class=''>";
                echo $form->row(
                    $form->select('code_country_phone_leader')->setLabel('')->setLabelOption(false)->setOptions($options_code),
                    $form->text('phone_leader')->setLabel('')->setLabelOption(false)
                )->setTitle('Phone number (for internal record)');
                echo "</div>";
        echo "</div>";



        echo "<div class='uk-grid uk-grid-collapse' uk-grid>";

            echo "<div class='uk-width-1-2'>";
            echo $form->row(
                $form->select('undergraduate_fees_min_currency')->setLabel('')->setOptions($options_currency)->setSetting('default', null)->setLabelOption(false),
                $form->text('undergraduate_fees_min_amount')->setType('number')->setLabel('')->setAttribute('placeholder', 'Amount')->setLabelOption(false)

            )->setTitle('Undergraduate Tuition Fees (Min)');
            echo "</div>";

            echo "<div class='uk-width-1-2'>";
            echo $form->row(
                $form->select('undergraduate_fees_max_currency')->setLabel('')->setOptions($options_currency)->setSetting('default', null)->setLabelOption(false),
                $form->text('undergraduate_fees_max_amount')->setType('number')->setLabel('')->setAttribute('placeholder', 'Amount')->setLabelOption(false)


            )->setTitle('Undergraduate Tuition Fees (Max)');
            echo "</div>";

        echo "</div>";

        echo "<div class='uk-grid uk-margin-remove-top uk-grid-collapse' uk-grid>";

            echo "<div class='uk-width-1-2'>";
            echo $form->row(
                $form->select('postgraduate_fees_min_currency')->setLabel('Currency')->setOptions($options_currency)->setSetting('default', null)->setLabelOption(false),
                $form->text('postgraduate_fees_min_amount')->setType('number')->setLabel('Amount')->setLabelOption(false)->setAttribute('placeholder', 'Amount')


            )->setTitle('Postgraduate Tuition fees (Min)');
            echo "</div>";

            echo "<div class='uk-width-1-2'>";
            echo $form->row(
                $form->select('postgraduate_fees_max_currency')->setLabel('Currency')->setOptions($options_currency)->setSetting('default', null)->setLabelOption(false),
                $form->text('postgraduate_fees_max_amount')->setType('number')->setLabel('Amount')->setLabelOption(false)->setAttribute('placeholder', 'Amount')


            )->setTitle('Postgraduate Tuition fees (Max)');
            echo "</div>";

        echo "</div>";
    };

    $media = function() use ($form) {

        echo $form->image('logo')->setLabel('Logo of university');
        echo $form->text('video_link')->setLabel('Video Link of university');
        echo "<hr />";

        echo $form->gallery('Gallery')->setSetting('button', 'Insert Images');
    };

    $admission = function() use ($form){

        echo $form->wpEditor('key_info')->setLabel('Key Infos')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);
        echo "<hr />";
        echo $form->wpEditor('admission')->setLabel('Admission Requirements')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);
        echo "<hr />";
        echo $form->wpEditor('how_apply')->setLabel('How to apply')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);
        echo "<hr />";
        echo $form->wpEditor('foreign_student')->setLabel('Foreign Students:')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);

    };

    $location = function() use ($form){

        echo $form->wpEditor('location')->setLabel('Location')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);
        echo $form->wpEditor('scholarship')->setLabel('Scholarships')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);

    };

    $contacts = function () use ($form) {

        global $post;

        $options_code = [
            'Select code country' => null
        ];

        $code_country = code_country();
        ksort($code_country);
        foreach($code_country as $key => $data):
            $options_code[$data['name']." - (+".$data['code'].")"] = '(+'.$data['code'].')';
        endforeach;

        $repeater_child = $form->repeater('emails')->setFields([
            $form->text('email')->setLabel('email')
        ])->setLabel('Emails');

        $repeater = $form->repeater('contacts')->setFields([
            $form->text('name')->setLabel('Name of contact'),
            $form->text('post')->setLabel('Poste/Role of contact'),
            $form->row(
                $form->select('country_code')->setLabel('')->setLabelOption(false)->setOptions($options_code),
                $form->text('phone')->setLabel('')->setLabelOption(false)
            )->setTitle('Phone of contact'),
            $repeater_child
        ])->setLabel('Contacts');

        echo $repeater;

    };

    $faculty = function() use ($form){

        $repeater_child_child = $form->repeater('faculties')->setFields([
            $form->text('name')->setLabel('Name')
        ])->setLabel('SubSubFaculty');

        $repeater_child = $form->repeater('faculties')->setFields([
            $form->text('name')->setLabel('Name'),
            $repeater_child_child
        ])->setLabel('SubFaculty');

        $repeater = $form->repeater('faculties')->setFields([
            $form->text('name')->setLabel('Name'),
            $repeater_child
        ])->setLabel('Faculty and Department');

        echo $repeater;
    };

    $premium = function () use ($form){

        echo "<div class='uk-grid uk-margin-remove-top uk-grid-collapse' uk-grid>";

            echo "<div class='uk-width-1-2'>";
            echo $form->text('whatsapp_number')->setLabel('Whatsapp Number');
            echo "</div>";

            echo "<div class='uk-width-1-2'>";
            echo $form->text('website_url')->setLabel('Website URL');
            echo "</div>";

        echo "</div>";

        $repeater = $form->repeater('whyStudy')->setFields([
            $form->text('title')->setLabel('Title of reason'),
            $form->wpEditor('content')->setLabel('Content of reason')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH'])
        ])->setLabel('Why Study at this University');

        echo $repeater;

        $repeater = $form->repeater('officials')->setFields([
            $form->image('image')->setLabel('Picture of official'),
            $form->row(
                $form->text('name')->setLabel('Name of official'),
                $form->text('post')->setLabel('Poste of official')
            )
        ])->setLabel('Senior Official');

        echo $repeater;

        echo $form->image('cover')->setLabel('Cover of university');

    };

    $tabs = tr_tabs();


    $tabs->addTab('Key Info', $about);
    $tabs->addTab('Admission', $admission);
    $tabs->addTab('Location', $location);
    $tabs->addTab('Faculty/Department', $faculty);
    $tabs->addTab('Uni Contacts', $contacts);
    $tabs->addTab('Photo and Video', $media);
    $tabs->addTab('Premium Field', $premium);


    $tabs->render('box');


});


$box2 = tr_meta_box('statistique_course_data')->setLabel('Courses');
$box2->addPostType($post_type->getId());
$box2->setPriority('high');
$box2->setContext('side');
$box2->setCallback(function(){

    global $post;

    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'university',
                'value' => $post->ID,
                'compare' => '='
            )
        )
    );

    $courses = new WP_Query($args);

    $terms = get_terms( 'study_level', array(
        'hide_empty' => true,
    ) );

    echo "<div class='uk-padding-small'>";

    echo "<h4>No of Courses  : ".$courses->post_count." </h4>";

    foreach ( $terms as $term ) {

        $args['tax_query'] = array(
            array(
                'taxonomy' => 'study_level',
                'field' => 'term_id',
                'terms' => $term->term_id,
            )
        );

        $study_level = new WP_Query($args);

        if($study_level->post_count){

            echo "<h6 class='uk-margin-small'>".$term->name." : ".$study_level->post_count."</h6>";
        }

    }

    echo "</div>";
});
