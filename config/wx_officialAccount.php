<?php

return [
    /**
     * �˺Ż�����Ϣ�����΢�Ź���ƽ̨/����ƽ̨��ȡ
     */
    'app_id'  => 'AppID',         // AppID
    'secret'  => 'AppSecret',     // AppSecret
    'token'   => 'Token',          // Token
    'aes_key' => 'EncodingAESKey',                    // EncodingAESKey�������밲ȫģʽ����һ��Ҫ��д������

    /**
     * ָ�� API ���÷��ؽ�������ͣ�array(default)/collection/object/raw/�Զ�������
     * ʹ���Զ�������ʱ�����캯���������һ�� `EasyWeChat\Kernel\Http\Response` ʵ��
     */
    'response_type' => 'array',

    /**
     * ��־����
     *
     * level: ��־����, ��ѡΪ��
     *         debug/info/notice/warning/error/critical/alert/emergency
     * path����־�ļ�λ��(����·��!!!)��Ҫ���дȨ��
     */
//    'log' => [
//        'default' => 'dev', // Ĭ��ʹ�õ� channel�������������Ը�Ϊ����� prod
//        'channels' => [
//            // ���Ի���
//            'dev' => [
//                'driver' => 'single',
//                'path' => '/tmp/easywechat.log',
//                'level' => 'debug',
//            ],
//            // ��������
//            'prod' => [
//                'driver' => 'daily',
//                'path' => '/tmp/easywechat.log',
//                'level' => 'info',
//            ],
//        ],
//    ],

    /**
     * �ӿ�����������ã���ʱʱ��ȣ�������ò�����ο���
     * http://docs.guzzlephp.org/en/stable/request-config.html
     *
     * - retries: ���Դ�����Ĭ�� 1��ָ���� http ����ʧ��ʱ���ԵĴ�����
     * - retry_delay: �����ӳټ������λ��ms����Ĭ�� 500
     * - log_template: ָ�� HTTP ��־ģ�壬��ο���https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */
    'http' => [
        'max_retries' => 1,
        'retry_delay' => 500,
        'timeout' => 5.0,
        // 'base_uri' => 'https://api.weixin.qq.com/', // ������ڹ�����Ҫ����Ĭ�ϵ� url ��ʱ���ʹ�ã����ݲ�ͬ��ģ�����ò�ͬ�� uri
    ],

    /**
     * OAuth ����
     *
     * scopes������ƽ̨��snsapi_userinfo / snsapi_base��������ƽ̨��snsapi_login
     * callback��OAuth��Ȩ��ɺ�Ļص�ҳ��ַ
     */
    'oauth' => [
        'scopes'   => ['snsapi_userinfo'],
        'callback' => '/?s=WeChat.oauth_callback',
    ],
];