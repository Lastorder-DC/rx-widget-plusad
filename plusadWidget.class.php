<?php
/**
 * @brief  한줄광고 출력 위젯 
 * @author 쿡래빗 (samswnlee@naver.com) 
 **/
class plusadWidget extends WidgetHandler {
	/**
	 * @brief 위젯의 실행 부분
	 * ./widgets/위젯/conf/info.xml에 선언한 extra_vars를 args로 받는다
	 * 결과값은 return 해준다.
	 **/
	function proc($args){
		// 광고 모듈이 설치되어 있지 않은 경우 실행중지
		if(!file_exists(_XE_PATH_.'modules/plusad/plusad.view.php')) return;
		
		// 위젯 설정에서 넘어온 변수들을 확인하고 기본 값 지정
		if(!(int)$args->content_cut_size) $args->content_cut_size = 20; // 광고 길이 제한 (기본값 : 20)
		if(!(int)$args->scroll_speed) $args->scroll_speed = 50; // 스크롤 속도 (기본값 : 50)
		if(!(int)$args->scroll_delay) $args->scroll_delay = 1000; // 스크롤 간격 (기본값 : 1000)
		if(!$args->name) $args->name = 'plusad'; //모듈이름 (기본값 : plusad)
		
		// 위젯 정보에 넣기
		$widget_info = new stdClass;
		$widget_info->content_cut_size = $args->content_cut_size;
		$widget_info->scroll_speed = $args->scroll_speed;
		$widget_info->scroll_delay = $args->scroll_delay;
		$widget_info->ad_point_use = $args->ad_point_use;
		$widget_info->name = $args->name;
		$widget_info->nick_name = $args->nick_name;
		$widget_info->link_type = $args->link_type;
		
		//템플릿에 보내기 위해 위젯설정값 세팅
		Context::set('widget_info', $widget_info);
		
		//진행중인 광고 목록 가져옴
		$oPlusadModel = getModel('plusad');
		$output = $oPlusadModel->getadlist($args);
		
		//광고목록 템플릿에 보내기 위해 세팅
		if (is_array($output->data) && count($output->data) > 1) {
            shuffle($output->data);
        } else {
            $output->data = [];
        }
		Context::set('ad_list',$output->data);
		
		// 템플릿의 스킨 경로를 지정 (skin, colorset에 따른 값을 설정)
		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		Context::set('colorset', $args->colorset);
	
		// 템플릿 파일명
		$tpl_file = 'default';
	
		// 템플릿 컴파일
		$oTemplate = TemplateHandler::getInstance();
		return $oTemplate->compile($tpl_path, $tpl_file);
	
	}
			
}