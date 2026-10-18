<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_name = get_bloginfo( 'name' );
$site_url = home_url( '/' );
$submitted_time = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
$email_title = ! empty( $subject ) ? $subject : __( '收到新的表单提交', 'xintheme-pagebuilder' );
$page_title = ! empty( $source_page_title ) ? $source_page_title : get_the_title();
$page_url = ! empty( $source_page_url ) ? $source_page_url : get_permalink();
$ip_address = ! empty( $submitter_ip ) ? $submitter_ip : '';
?>
<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo esc_html( $email_title ); ?></title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;color:#1d2327;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:14px;line-height:1.6;">
	<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;padding:0;margin:0;">
		<tr>
			<td align="center" style="padding:28px 12px;">
				<table role="presentation" width="640" cellpadding="0" cellspacing="0" style="width:640px;max-width:100%;border-collapse:collapse;background:#ffffff;border:1px solid #f5f5f5;">
					<tr>
						<td style="padding:24px 28px;background:#000;color:#ffffff;">
							<div style="font-size:13px;line-height:1.4;color:#cbd5e1;margin:0 0 6px;">
								<?php echo esc_html( $site_name ); ?>
							</div>
							<h1 style="margin:0;font-size:22px;line-height:1.35;font-weight:700;color:#ffffff;">
								<?php echo esc_html( $email_title ); ?>
							</h1>
						</td>
					</tr>

					<tr>
						<td style="padding:22px 28px 10px;">
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;background:#f5f5f5;border:1px solid #e2e8f0;">
								<tr>
									<td style="padding:12px 14px;color:#64748b;font-size:13px;width:92px;border-bottom:1px solid #e2e8f0;">
										<?php esc_html_e( '提交时间', 'xintheme-pagebuilder' ); ?>
									</td>
									<td style="padding:12px 14px;color:#1d2327;font-size:13px;border-bottom:1px solid #e2e8f0;">
										<?php echo esc_html( $submitted_time ); ?>
									</td>
								</tr>
								<?php if ( $page_title && $page_url ) : ?>
								<tr>
									<td style="padding:12px 14px;color:#64748b;font-size:13px;width:92px;<?php echo $ip_address ? 'border-bottom:1px solid #e2e8f0;' : ''; ?>">
										<?php esc_html_e( '来源页面', 'xintheme-pagebuilder' ); ?>
									</td>
									<td style="padding:12px 14px;color:#1d2327;font-size:13px;<?php echo $ip_address ? 'border-bottom:1px solid #e2e8f0;' : ''; ?>">
										<a href="<?php echo esc_url( $page_url ); ?>" style="color:#2563eb;text-decoration:none;" target="_blank"><?php echo esc_html( $page_url ); ?></a>
										<?php if ( $page_title ) : ?>
											<br><span style="color:#64748b;font-size:12px;"><?php echo esc_html( $page_title ); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<?php endif; ?>
								<?php if ( $ip_address ) : ?>
								<tr>
									<td style="padding:12px 14px;color:#64748b;font-size:13px;width:92px;">
										<?php esc_html_e( '提交者 IP', 'xintheme-pagebuilder' ); ?>
									</td>
									<td style="padding:12px 14px;color:#1d2327;font-size:13px;">
										<?php echo esc_html( $ip_address ); ?>
									</td>
								</tr>
								<?php endif; ?>
							</table>
						</td>
					</tr>

					<tr>
						<td style="padding:14px 28px 28px;">
							<?php if ( ! empty( $email_custom_message ) ) : ?>
								<div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">
									<div style="padding:12px 14px;background:#f5f5f5;color:#475569;font-weight:700;font-size:13px;border-bottom:1px solid #e2e8f0;">
										<?php esc_html_e( '消息内容', 'xintheme-pagebuilder' ); ?>
									</div>
									<div style="padding:16px 14px;color:#111827;font-size:14px;line-height:1.7;">
										<?php echo wp_kses_post( wpautop( $email_custom_message ) ); ?>
									</div>
								</div>
							<?php else : ?>
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;overflow:hidden;">
								<tr>
									<td style="padding:12px 14px;background:#f5f5f5;color:#475569;font-weight:700;font-size:13px;width:32%;border-bottom:1px solid #e2e8f0;">
										<?php esc_html_e( '字段', 'xintheme-pagebuilder' ); ?>
									</td>
									<td style="padding:12px 14px;background:#f5f5f5;color:#475569;font-weight:700;font-size:13px;border-bottom:1px solid #e2e8f0;">
										<?php esc_html_e( '内容', 'xintheme-pagebuilder' ); ?>
									</td>
								</tr>
								<?php foreach ( $submittedFormData as $data ) : ?>
									<?php
									$field_label = isset( $data['label'] ) ? $data['label'] : '';
									$field_value = isset( $data['submitted_data'] ) ? $data['submitted_data'] : '';
									if ( is_array( $field_value ) ) {
										$field_value = implode( "\n", array_map( 'sanitize_text_field', $field_value ) );
									}
									?>
									<tr>
										<td valign="top" style="padding:13px 14px;color:#475569;font-weight:600;font-size:14px;border-bottom:1px solid #eef2f7;width:32%;">
											<?php echo esc_html( $field_label ); ?>
										</td>
										<td valign="top" style="padding:13px 14px;color:#111827;font-size:14px;border-bottom:1px solid #eef2f7;">
											<?php echo '' !== trim( (string) $field_value ) ? nl2br( esc_html( $field_value ) ) : '<span style="color:#94a3b8;">-</span>'; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</table>
							<?php endif; ?>
						</td>
					</tr>

					<tr>
						<td style="padding:18px 28px;background:#f5f5f5;color:#64748b;font-size:12px;text-align:center;">
							<?php esc_html_e( '此邮件由系统自动发送，请勿直接回复。', 'xintheme-pagebuilder' ); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
