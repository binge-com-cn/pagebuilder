<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'XTPB_Layout_Generator' ) ) {
	class XTPB_Layout_Generator {
		public function generate( $settings = array() ) {
			ob_start();
			if ( ! empty( $settings ) ) {
				$this->generate_layout( $settings );
			}
			return ob_get_clean();
		}

		public function row_stretch_class( $setting = array(), $affected_row = 'row-parent' ) {
			$row_dynamic_class = " xtpb-row-{$setting['id']} ";
			if ( 'row-parent' === $affected_row ) {
				if ( isset( $setting['settings']['row_screen'] ) && ( 'row-stretch' === $setting['settings']['row_screen']
					|| 'row-container-stretch' === $setting['settings']['row_screen']
					|| 'container-stretch-no-gutter' === $setting['settings']['row_screen'] ) ) {
					return $row_dynamic_class;
				}
			} elseif ( 'container' === $affected_row ) {
				if ( ! isset( $setting['settings']['row_screen'] ) || 'row-default' === $setting['settings']['row_screen'] ) {
					return $row_dynamic_class . ' xtpb-container-row-default';
				}
			}
			return '';
		}

		private function get_responsive_value( $value, $fallback = '' ) {
			if ( is_array( $value ) ) {
				if ( isset( $value['md'] ) && '' !== $value['md'] ) {
					return $value['md'];
				}

				foreach ( $value as $item ) {
					if ( '' !== $item && null !== $item ) {
						return $item;
					}
				}
			}

			if ( '' !== $value && null !== $value ) {
				return $value;
			}

			return $fallback;
		}

		private function get_visible_columns( $columns ) {
			if ( empty( $columns ) || ! is_array( $columns ) ) {
				return array();
			}

			return array_values(
				array_filter(
					$columns,
					function( $column ) {
						return ! empty( $column['visibility'] );
					}
				)
			);
		}

		private function get_grid_template_columns( $setting ) {
			$columns = ! empty( $setting['columns'] ) && is_array( $setting['columns'] )
				? $this->get_visible_columns( $setting['columns'] )
				: array();

			$count = count( $columns );

			if ( ! empty( $setting['layout'] ) ) {
				$parts = array_filter( array_map( 'trim', explode( ',', $setting['layout'] ) ) );

				if ( ! empty( $parts ) ) {
					$fr = array();

					foreach ( $parts as $part ) {
						$value = (float) $part;

						if ( $value > 0 ) {
							$fr[] = $value . 'fr';
						}
					}

					if ( ! empty( $fr ) ) {
						return implode( ' ', $fr );
					}
				}
			}

			if ( $count > 0 ) {
				return 'repeat(' . $count . ', minmax(0, 1fr))';
			}

			return '1fr';
		}

		private function get_row_gap( $setting ) {
			$gap = $this->get_responsive_value( isset( $setting['settings']['row_custom_gutter'] ) ? $setting['settings']['row_custom_gutter'] : '', '' );

			if ( '' !== $gap ) {
				return (int) $gap . 'px';
			}

			if ( function_exists( 'xtpb_get_option' ) ) {
				$option_gap = (int) xtpb_get_option( 'xtpb_col_spacing' );

				if ( $option_gap > 0 ) {
					return $option_gap . 'px';
				}
			}

			return '30px';
		}

		private function get_row_grid_style( $setting ) {
			return sprintf(
				'grid-template-columns:%s;--xtpb-gap:%s;',
				esc_attr( $this->get_grid_template_columns( $setting ) ),
				esc_attr( $this->get_row_gap( $setting ) )
			);
		}

		private function render_column_addons( $col ) {
			if ( empty( $col['addons'] ) || ! is_array( $col['addons'] ) ) {
				return;
			}

			foreach ( $col['addons'] as $addon ) {
				$addon_type = ! empty( $addon['type'] ) ? $addon['type'] : '';

				if ( 'inner_row' === $addon_type ) {
					$this->generate_inner_section( $addon );
					continue;
				}

				if ( isset( $addon['visibility'] ) && ! $addon['visibility'] ) {
					continue;
				}

				$this->render_addon_item( $addon, $addon_type );
			}
		}

		private function render_addon_item( $addon, $addon_type ) {
			$addon_animation_class = '';
			$addon_animation_data  = '';

			if ( isset( $addon['settings']['addon_animation'] ) ) {
				$is_enable_animation = 0;

				if ( isset( $addon['settings']['addon_animation']['itemOpen'] ) ) {
					$is_enable_animation = (int) $addon['settings']['addon_animation']['itemOpen'];
				}

				if ( $is_enable_animation ) {
					$addon_animation_class = isset( $addon['settings']['addon_animation']['name'] )
						? ' xtpb-wow ' . $addon['settings']['addon_animation']['name']
						: '';

					$delay    = isset( $addon['settings']['addon_animation']['delay'] ) ? $addon['settings']['addon_animation']['delay'] : '';
					$duration = isset( $addon['settings']['addon_animation']['duration'] ) ? $addon['settings']['addon_animation']['duration'] : '';

					if ( '' !== $delay ) {
						$addon_animation_data .= ' data-wow-delay="' . esc_attr( $delay ) . 'ms"';
					}

					if ( '' !== $duration ) {
						$addon_animation_data .= ' data-wow-duration="' . esc_attr( $duration ) . 'ms"';
					}
				}
			}

			$addon_id = ! empty( $addon['id'] ) ? $addon['id'] : '';

			$addon_css_class = isset( $addon['settings']['addon_class'] ) ? ' ' . $addon['settings']['addon_class'] : '';
			$addon_css_id    = isset( $addon['settings']['addon_id'] ) ? ' id="' . esc_attr( $addon['settings']['addon_id'] ) . '"' : '';
			$addon_name      = ! empty( $addon['name'] ) ? sanitize_key( $addon['name'] ) : '';

			$addon_semantic_classes = ' xtpb-addon-node';
			if ( $addon_name ) {
				$addon_semantic_classes .= ' xtpb-addon--' . $addon_name;
			}

			$addon_type_class = '';
			if ( 'addon' !== $addon_type ) {
				$addon_type_class = 'xtpb-' . $addon_type . '-' . $addon_id . ' ';
			}

			$classes = trim(
				'xtpb-addon ' .
				$addon_type_class .
				'xtpb-addon-' . $addon_id .
				$addon_semantic_classes .
				$addon_animation_class .
				$addon_css_class
			);

			$addon_markup = '';
			if ( 'addon' === $addon_type || 'inner_addon' === $addon_type ) {

				$addon_name     = $addon['name'] ?? '';
				$addon_instance = addon_instance($addon_name);

				if (!$addon_instance) {
					error_log(sprintf(
						'[XTPB 缺失模块] 页面ID：%d，模块名：%s，模块ID：%s，类型：%s',
						get_queried_object_id(),
						$addon_name,
						$addon['id'] ?? '',
						$addon_type
					));

					return;
				}

				if (method_exists($addon_instance, 'render')) {
					$addon_markup = $addon_instance->render($addon);
				}

			}

			echo '<div' . $addon_css_id . ' class="' . esc_attr( $classes ) . '"' . $addon_animation_data . ' data-addon-id="' . esc_attr( $addon_id ) . '">';
			echo $addon_markup;
			echo '</div>';
		}

		private function get_column_classes( $col, $extra_classes = '' ) {
			$col_css_class = isset( $col['settings']['col_class'] ) ? $col['settings']['col_class'] : '';
			$col_animation = isset( $col['settings']['col_animation'] ) ? $col['settings']['col_animation'] : array();

			if ( ! empty( $col_animation['itemOpen'] ) ) {
				$col_css_class .= isset( $col_animation['name'] ) ? ' xtpb-wow ' . $col_animation['name'] : '';
			}

			return trim( 'xtpb-column xtpb-col-' . $col['id'] . ' ' . $col_css_class . ' ' . $extra_classes );
		}

		private function get_column_animation_data( $col ) {
			$col_animation = isset( $col['settings']['col_animation'] ) ? $col['settings']['col_animation'] : array();
			$output        = '';

			if ( ! empty( $col_animation['itemOpen'] ) ) {
				if ( isset( $col_animation['delay'] ) && '' !== $col_animation['delay'] ) {
					$output .= ' data-wow-delay="' . esc_attr( $col_animation['delay'] ) . 'ms"';
				}

				if ( isset( $col_animation['duration'] ) && '' !== $col_animation['duration'] ) {
					$output .= ' data-wow-duration="' . esc_attr( $col_animation['duration'] ) . 'ms"';
				}
			}

			return $output;
		}

		public function generate_layout( $settings = array() ) {
			foreach ( $settings as $setting ) {
				if ( empty( $setting['visibility'] ) ) {
					continue;
				}

				$animation_attr  = '';
				$animation_class = '';
				if ( ! empty( $setting['settings']['row_animation'] ) ) {
					$animation_settings = (object) $setting['settings']['row_animation'];
					if ( ! empty( $animation_settings->itemOpen ) ) {
						$animation_class = " xtpb-wow {$animation_settings->name} ";
						$animation_attr  = " data-wow-duration='{$animation_settings->duration}ms' data-wow-delay='{$animation_settings->delay}ms' ";
					}
				}

				$full_container_stretch = array();
				if ( ! empty( $setting['settings']['row_screen'] ) ) {
					if ( 'row-container-stretch' === $setting['settings']['row_screen'] ) {
						$full_container_stretch[] = 'xtpb-container-full';
					} elseif ( 'container-stretch-no-gutter' === $setting['settings']['row_screen'] ) {
						array_push( $full_container_stretch, 'xtpb-container-full', 'xtpb-container-no-gutter' );
					}
				}

				$parent_row_class   = $this->row_stretch_class( $setting );
				$row_container_class = $this->row_stretch_class( $setting, 'container' );
				$row_css_id          = isset( $setting['settings']['row_id'] ) ? 'id="' . esc_attr( $setting['settings']['row_id'] ) . '"' : '';
				$row_css_class       = isset( $setting['settings']['row_class'] ) ? $setting['settings']['row_class'] : '';
				?>
				<section <?php echo $row_css_id; ?> class="xtpb-section <?php echo esc_attr( $parent_row_class . $animation_class . $row_css_class ); ?>" <?php echo $animation_attr; ?> >
					<?php if ( ! empty( $setting['settings']['row_shape'] ) && 1 == $setting['settings']['row_shape']['itemOpenShape'] && $setting['settings']['row_screen'] != 'row-default' ) { ?>
						<div class="xtpb-shape-container xtpb-top-shape">
							<?php echo file_get_contents( XTPB_DIR_PATH . 'assets/shapes/' . $setting['settings']['row_shape']['shapeStyle'] . '.svg' ); ?>
						</div>
					<?php } ?>
					<?php if ( ! empty( $setting['settings']['row_shape_bottom'] ) && 1 == $setting['settings']['row_shape_bottom']['itemOpenShape'] && $setting['settings']['row_screen'] != 'row-default' ) { ?>
						<div class="xtpb-shape-container xtpb-bottom-shape">
							<?php echo file_get_contents( XTPB_DIR_PATH . 'assets/shapes/' . $setting['settings']['row_shape_bottom']['shapeStyle'] . '.svg' ); ?>
						</div>
					<?php } ?>

					<?php
					if ( isset( $setting['settings']['row_background'] ) ) {
						if ( isset( $setting['settings']['row_background']['bgVideo']['url'] ) || isset( $setting['settings']['row_background']['bgExternalVideo'] ) ) {
							echo $this->bg_video( $setting['settings']['row_background'] );
						}
					}
					?>

					<div class="xtpb-section__inner <?php echo esc_attr( trim( $row_container_class . implode( ' ', $full_container_stretch ) ) ); ?>">
						<?php if ( ! empty( $setting['settings']['row_shape'] ) && 1 == $setting['settings']['row_shape']['itemOpenShape'] && $setting['settings']['row_screen'] == 'row-default' ) { ?>
							<div class="xtpb-shape-container xtpb-top-shape">
								<?php echo file_get_contents( XTPB_DIR_PATH . 'assets/shapes/' . $setting['settings']['row_shape']['shapeStyle'] . '.svg' ); ?>
							</div>
						<?php } ?>
						<?php if ( ! empty( $setting['settings']['row_shape_bottom'] ) && 1 == $setting['settings']['row_shape_bottom']['itemOpenShape'] && $setting['settings']['row_screen'] == 'row-default' ) { ?>
							<div class="xtpb-shape-container xtpb-bottom-shape">
								<?php echo file_get_contents( XTPB_DIR_PATH . 'assets/shapes/' . $setting['settings']['row_shape_bottom']['shapeStyle'] . '.svg' ); ?>
							</div>
						<?php } ?>

						<div class="xtpb-section__grid" style="<?php echo esc_attr( $this->get_row_grid_style( $setting ) ); ?>">
							<?php
							if ( ! empty( $setting['columns'] ) ) {
								foreach ( $setting['columns'] as $col ) {
									if ( empty( $col['visibility'] ) ) {
										continue;
									}

									$col_css_id = isset( $col['settings']['col_id'] ) ? $col['settings']['col_id'] : '';
									?>
									<div <?php if ( $col_css_id ) { echo 'id="' . esc_attr( $col_css_id ) . '"'; } ?> class="<?php echo esc_attr( $this->get_column_classes( $col ) ); ?>" <?php echo $this->get_column_animation_data( $col ); ?> >
										<?php
										if ( isset( $col['settings']['col_background'] ) ) {
											if ( isset( $col['settings']['col_background']['bgVideo']['url'] ) || isset( $col['settings']['col_background']['bgExternalVideo'] ) ) {
												echo $this->bg_video( $col['settings']['col_background'] );
											}
										}

										$this->render_column_addons( $col );
										?>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</section>
				<?php
			}
		}

		public function generate_inner_section( $setting = array() ) {
			if ( empty( $setting['visibility'] ) ) {
				return;
			}

			$animation_attr  = '';
			$animation_class = '';
			if ( ! empty( $setting['settings']['row_animation'] ) ) {
				$animation_settings = (object) $setting['settings']['row_animation'];
				if ( ! empty( $animation_settings->itemOpen ) ) {
					$animation_class = ' xtpb-wow xtpb-animated ' . $animation_settings->name;
					if ( $animation_settings->duration ) {
						$animation_attr .= ' data-wow-duration="' . esc_attr( $animation_settings->duration ) . 'ms"';
					}
					if ( $animation_settings->delay ) {
						$animation_attr .= ' data-wow-delay="' . esc_attr( $animation_settings->delay ) . 'ms"';
					}
				}
			}
			$row_css_id    = isset( $setting['settings']['row_id'] ) ? 'id="' . esc_attr( $setting['settings']['row_id'] ) . '"' : '';
			$row_css_class = isset( $setting['settings']['row_class'] ) ? $setting['settings']['row_class'] : '';
			?>
			<div <?php echo $row_css_id; ?> class="xtpb-inner-section xtpb-row-<?php echo esc_attr( $setting['id'] ); ?><?php echo esc_attr( $animation_class ); ?> <?php echo esc_attr( $row_css_class ); ?>"<?php echo $animation_attr; ?>>
				<?php
				if ( isset( $setting['settings']['row_background'] ) ) {
					if ( isset( $setting['settings']['row_background']['bgVideo']['url'] ) || isset( $setting['settings']['row_background']['bgExternalVideo'] ) ) {
						echo $this->bg_video( $setting['settings']['row_background'] );
					}
				}
				?>

				<div class="xtpb-inner-section__inner">
					<div class="xtpb-inner-section__grid" style="<?php echo esc_attr( $this->get_row_grid_style( $setting ) ); ?>">
						<?php
						if ( ! empty( $setting['columns'] ) ) {
							foreach ( $setting['columns'] as $col ) {
								if ( empty( $col['visibility'] ) ) {
									continue;
								}

								$col_css_id = isset( $col['settings']['col_id'] ) ? $col['settings']['col_id'] : '';
								?>
								<div <?php if ( $col_css_id ) { echo 'id="' . esc_attr( $col_css_id ) . '"'; } ?> class="<?php echo esc_attr( $this->get_column_classes( $col ) ); ?>" <?php echo $this->get_column_animation_data( $col ); ?>>
									<?php
									if ( isset( $col['settings']['col_background'] ) ) {
										if ( isset( $col['settings']['col_background']['bgVideo']['url'] ) || isset( $col['settings']['col_background']['bgExternalVideo'] ) ) {
											echo $this->bg_video( $col['settings']['col_background'] );
										}
									}

									$this->render_column_addons( $col );
									?>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}

		public function bg_video( $video_settings = null ) {
			if ( ! isset( $video_settings['videoType'] ) ) {
				return '';
			}
			if ( 'local' === $video_settings['videoType'] ) {
				if ( ! isset( $video_settings['bgVideo']['url'] ) ) {
					return '';
				}
				return '<div class="xtpb-video-bg-wrap"><video class="xtpb-video-bg" autoPlay muted loop><source src="' . $video_settings['bgVideo']['url'] . '" /></video></div>';
			} elseif ( 'external' === $video_settings['videoType'] && $video_settings['bgExternalVideo'] ) {
				$videoData = wp_oembed_get( $video_settings['bgExternalVideo'] );
				if ( strpos( $video_settings['bgExternalVideo'], 'yout' ) !== false ) {
					return '<div class="xtpb-video-bg-wrap">' . str_replace( '?feature=oembed', '?feature=oembed&autoplay=1', $videoData ) . '</div>';
				}
				if ( strpos( $video_settings['bgExternalVideo'], 'vimeo' ) !== false ) {
					return '<div class="xtpb-video-bg-wrap">' . str_replace( '?', '?autoplay=1&loop=1&', $videoData ) . '</div>';
				}
				return '<div class="xtpb-video-bg-wrap">' . $videoData . '</div>';
			}
		}
	}
}
