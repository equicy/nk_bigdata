<?php

/**
 * Route Config.
 */
$_ROUTES = [
    '/' => ['GET', 'Example', 'helloWorld'],
    'login' => ['POST', 'User', 'signIn'],
    'signup' => ['POST', 'User', 'signUp'],
    'tj/outbound' => ['GET', 'TjOutbound', 'get', 'Middleware@normal'],
    'tj/inbound' => ['GET', 'TjInbound', 'get', 'Middleware@normal'],
    'tj/otherprovinces' => ['GET', 'TjOtherProvinces', 'get', 'Middleware@normal'],
    'tj/tourist' => ['GET', 'TjTourist', 'get', 'Middleware@normal'],
    'manager/tj/outbound/list' => ['GET', 'ManageTjOutbound', 'getList', 'Middleware@manager'],
    'manager/tj/outbound/insert' => ['POST', 'ManageTjOutbound', 'outboundInsert', 'Middleware@manager'],
    'manager/tj/outboundfile/update' => ['POST', 'ManageTjOutbound', 'outboundUpdate', 'Middleware@manager'],
    'manager/tj/exitportfile/update' => ['POST', 'ManageTjOutbound', 'exitportUpdate', 'Middleware@manager'],
    'manager/tj/outboundteams/update' => ['POST', 'ManageTjOutbound', 'totalTeamsUpdate', 'Middleware@manager'],
    'manager/tj/outboundpeople/update' => ['POST', 'ManageTjOutbound', 'totalPeopleUpdate', 'Middleware@manager'],
    'manager/tj/inbound/list' => ['GET', 'ManageTjInbound', 'getList', 'Middleware@manager'],//获取天津入境信息,参数year
    'manager/tj/inbound/insert' => ['POST', 'ManageTjInbound', 'inboundInsert', 'Middleware@manager'],//插入天津入境信息,参数year, month, team_number,people_number,文件entryport_team_number,文件inbound
    'manager/tj/inboundfile/update' => ['POST', 'ManageTjInbound', 'inboundUpdate', 'Middleware@manager'],//更新天津入境信息,参数id,文件inbound
    'manager/tj/entryportfile/update' => ['POST', 'ManageTjInbound', 'entryportUpdate', 'Middleware@manager'],//更新天津入境信息,参数id,文件entryport_team_number
    'manager/tj/inboundteams/update' => ['POST', 'ManageTjInbound', 'totalTeamsUpdate', 'Middleware@manager'],//更新天津入境信息,参数id,team_number
    'manager/tj/inboundpeople/update' => ['POST', 'ManageTjInbound', 'totalPeopleUpdate', 'Middleware@manager'],//更新天津入境信息,参数id,people_number
    'manager/tj/otherprovinces/list' => ['GET', 'ManageTjOtherProvinces', 'getList', 'Middleware@manager'],//获取天津赴外省信息,参数year
    'manager/tj/otherprovinces/insert' => ['POST', 'ManageTjOtherProvinces', 'insert', 'Middleware@manager'],//插入天津赴外省信息,参数year, month, team_number,people_number,文件ranking,文件out_provinces
    'manager/tj/otherprovincesfile/update' => ['POST', 'ManageTjOtherProvinces', 'otherProUpdate', 'Middleware@manager'],//更新天津赴外省信息,参数id,文件out_provinces
    'manager/tj/otherprovincesranking/update' => ['POST', 'ManageTjOtherProvinces', 'rankingUpdate', 'Middleware@manager'],//更新天津赴外省信息,参数id,文件ranking
    'manager/tj/otherprovincesteams/update' => ['POST', 'ManageTjOtherProvinces', 'totalTeamsUpdate', 'Middleware@manager'],//更新天津赴外省信息,参数id,team_number
    'manager/tj/otherprovincespeople/update' => ['POST', 'ManageTjOtherProvinces', 'totalPeopleUpdate', 'Middleware@manager'],//更新天津赴外省信息,参数id,people_number
    'manager/tj/tourist/insert' => ['POST', 'ManageTjTouristAttractions', 'insert', 'Middleware@manager'],//插入天津景点计信息,参数year和文件attraction_data
    'manager/tj/tourist/update' => ['POST', 'ManageTjTouristAttractions', 'attractionUpdate', 'Middleware@manager'], //更新天津景点计信息,参数id和文件attraction_data
    'manager/country/outbound/insert' => ['POST', 'ManageCountrywideOutbound', 'outboundInsert', 'Middleware@manager'],
    'manager/country/outbound/update' => ['POST', 'ManageCountrywideOutbound', 'outboundUpdate', 'Middleware@manager'],
    'country/outbound' => ['GET', 'CountrywideOutbound', 'get', 'Middleware@manager'],
    'manager/country/inbound/insert' => ['POST', 'ManageCountrywideInbound', 'inboundInsert', 'Middleware@manager'],
    'manager/country/inbound/update' => ['POST', 'ManageCountrywideInbound', 'inboundUpdate', 'Middleware@manager'],
    'country/inbound' => ['GET', 'CountrywideInbound', 'get', 'Middleware@manager'],
    'manager/analysis/tourist' => ['POST', 'ManageTouristFeatureAnalysis', 'touristInsert', 'Middleware@manager'],
    'manager/analysis/tourist/age' => ['POST', 'ManageTouristFeatureAnalysis', 'ageUpdate', 'Middleware@manager'],
    'manager/analysis/tourist/education' => ['POST', 'ManageTouristFeatureAnalysis', 'educationUpdate', 'Middleware@manager'],
    'analysis/tourist' => ['GET', 'FeatureAnalysis', 'tourist', 'Middleware@manager'],
    'manager/analysis/spend' => ['POST', 'ManageSpendFeatureAnalysis', 'spendInsert', 'Middleware@manager'],
    'manager/analysis/spend/family' => ['POST', 'ManageSpendFeatureAnalysis', 'familyUpdate', 'Middleware@manager'],
    'manager/analysis/spend/asset' => ['POST', 'ManageSpendFeatureAnalysis', 'assetUpdate', 'Middleware@manager'],
    'manager/analysis/spend/job' => ['POST', 'ManageSpendFeatureAnalysis', 'jobUpdate', 'Middleware@manager'],
    'analysis/spend' => ['GET', 'FeatureAnalysis', 'spend', 'Middleware@manager'],
    'manager/analysis/focus' => ['POST', 'ManageFocusFeatureAnalysis', 'focusInsert', 'Middleware@manager'],
    'manager/analysis/focus/destination' => ['POST', 'ManageFocusFeatureAnalysis', 'destinationUpdate', 'Middleware@manager'],
    'manager/analysis/focus/focus-type' => ['POST', 'ManageFocusFeatureAnalysis', 'focusTypeUpdate', 'Middleware@manager'],
    'analysis/focus' => ['GET', 'FeatureAnalysis', 'focus', 'Middleware@manager'],
    'manager/analysis/supplyside' => ['POST', 'ManageSupplySideFeatureAnalysis', 'supplySideInsert', 'Middleware@manager'],
    'manager/analysis/supplyside/public-government' => ['POST', 'ManageSupplySideFeatureAnalysis', 'publicGovernmentUpdate', 'Middleware@manager'],
    'manager/analysis/supplyside/public-business' => ['POST', 'ManageSupplySideFeatureAnalysis', 'publicBusinessUpdate', 'Middleware@manager'],
    'analysis/supplyside' => ['GET', 'FeatureAnalysis', 'supplySide', 'Middleware@manager'],

];