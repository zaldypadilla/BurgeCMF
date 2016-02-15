<div class="main">
	<div class="container">
		<h1>{category_details_text} {category_id}
			<?php 
			if($info && $info[$selected_lang]['cd_name']) 
				echo $comma_text." ".$info[$selected_lang]['cd_name']
			?>
		</h1>		
		<?php 
			if(!$info) {
		?>
			<h4>{not_found_text}</h4>
		<?php 
			}else{ 
		?>
			<div class="container">
				<div class="row general-buttons">
					<div class="two columns half-col-margin button sub-primary button-type2" onclick="deleteCategory()">
						{delete_category_text}
					</div>
					<div class="two columns button sub-primary button-type1" onclick="addSubCategory()">
						{add_sub_category_text}
					</div>
				</div>
				<br><br>
				<?php echo form_open(get_admin_category_details_link($category_id),array()); ?>
					<input type="hidden" name="post_type" value="edit_category" />
					
					<div class="tab-container">
						<ul class="tabs">
							<?php foreach($info as $inf) { ?>
								<li>
									<a href="#cd_<?php echo $inf['cd_lang_id'];?>">
										<?php echo $inf['lang']?>
									</a>
								</li>
							<?php } ?>
						</ul>
						<script type="text/javascript">
							$(function(){
							   $('ul.tabs').each(function(){
									var $active, $content, $links = $(this).find('a');
									$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
									$active.addClass('active');

									$content = $($active[0].hash);

									$links.not($active).each(function () {
									   $(this.hash).hide();
									});

									$(this).on('click', 'a', function(e){
									   $active.removeClass('active');
									   $content.hide();

									   $active = $(this);
									   $content = $(this.hash);

									   $active.addClass('active');

									   $content.show();						   	

									   e.preventDefault();
									   
									   <?php if(0) { ?>
										   //since each tab has different height, 
										   //we should reequalize  height of sidebar and main div.
										   //may be a bad hack,
										   //which should be corrected in future versions.
										   //
										   //what should we  do ?
										   //we should allow developers to register a list of functions 
										   //to be called on document\.ready event,
										   //but each function has a priority, 
										   //so we can sort their execution by that priority.
										   //and this will solve the problem
										   //for example in this situation, in each load, we should first equalize height of
										   //all tabs, and then call setupMovingHeader 
										   //in this way we don't need to call setupMovingHeader in each tab change event
										<?php } ?>
									   setupMovingHeader();
									});
								});
							});
						</script>
						<?php foreach($info as $lang=>$cd) {?>
							<div class="tab" id="cd_<?php echo $cd['cd_lang_id'];?>">
								<div class="container">
									<div class="row even-odd-bg" >
										<div class="three columns">
											<span>{name_text}</span>
										</div>
										<div class="nine columns">
											<input type="text" class="full-width" 
												name="<?php echo $lang;?>[cd_name]" 
												value="<?php echo $cd['cd_name']; ?>"
												onkeyup="setUrl(this,'<?php echo $lang;?>');"
											/>
										</div>
									</div>
									<div class="row even-odd-bg" >
										<div class="three columns">
											<span>{url_text}</span>
										</div>
										<div class="nine columns eng ltr">
											{category_url_first_part}
											<input type="text" class="six columns ltr"
												name="<?php echo $lang;?>[cd_url]" 
												value="<?php echo $cd['cd_url']; ?>"
											/>
										</div>
									</div>
									<div class="row even-odd-bg" >
										<div class="three columns">
											<span>{description_text}</span>
										</div>
										<div class="nine columns ">
											<textarea class="full-width" rows="3"
												name="<?php echo $lang;?>[cd_description]"
											><?php echo $cd['cd_description']; ?></textarea>
										</div>
									</div>
									<div class="row even-odd-bg" >
										<div class="three columns">
											<span>{image_text}</span>
										</div>
										<div class="nine columns ">
											<input type="hidden" name="<?php echo $lang;?>[cd_image]"
											value="<?php echo $cd['cd_image']; ?>" />
											<?php
												$image=$no_image_url;
												if($cd['cd_image'])
													$image=$cd['cd_image'];
											?>
											<img 
												id="img-<?php echo $lang; ?>"
												src="<?php echo $image; ?>"  
												style="cursor:pointer;max-height:200px;background-color:white"
												onclick="selectImage('<?php echo $lang; ?>');"
											/>
										</div>

									</div>
									<div class="row even-odd-bg" >
										<div class="three columns">
											<span>{meta_keywords_text}</span>
										</div>
										<div class="nine columns">
											<input type="text" class="full-width" 
												name="<?php echo $lang;?>[cd_meta_keywords]" 
												value="<?php echo $cd['cd_meta_keywords']; ?>"
											/>
										</div>
									</div>
									<div class="row even-odd-bg" >
										<div class="three columns">
											<span>{meta_description_text}</span>
										</div>
										<div class="nine columns">
											<input type="text" class="full-width" 
												name="<?php echo $lang;?>[cd_meta_description]" 
												value="<?php echo $cd['cd_meta_description']; ?>"
											/>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<br>
					<div class="row even-odd-bg">
						<div class="three columns">
							<span>{parent_text}</span>
						</div>
						<div id="parent-category" class="nine columns category-div">
							<input type="hidden" name="category_parent_id" />
							<?php echo $categories; ?>
						</div>
						<script type="text/javascript">
							$("#parent-category span").click(
								function()
								{
									var id=$(this).data("id");
									$("#parent-category input[value="+id+"]").prop("checked",true);

								}
							);

							var activeLang;

							function selectImage(lang)
							{
								var fileMan=$(".burgeFileMan");
								if(!fileMan.length)
									createFileMan();

								fileMan.css("display","block");

								activeLang=lang;
							}

							function createFileMan()
							{
								var src="<?php echo get_link('admin_file_inline');?>";
								src+="?parent_function=fileSelected";
								$(document.body).append(
									"<div class='burgeFileMan'>"
										+"<div class='bmain'>"
										+	"<div class='bheader'>File Manager"
										+		"<button class='close' onclick='closeFileMan()'>×</button>"
										+ "</div>"
										+	"<iframe src='"+src+"'></iframe>"
										+"</div>"
									+"</div>"
								);
							}

							function closeFileMan()
							{
								var fileMan=$(".burgeFileMan");
								
								fileMan.css("display","none");	
							}

							function fileSelected(path)
							{
								$("#img-"+activeLang).prop("src",path);
								$("input[name='"+activeLang+"[cd_image]']").val(path);
								closeFileMan();
							}
						</script>
					</div>
					<br><br>
					<div class="row">
							<div class="four columns">&nbsp;</div>
							<input type="submit" class=" button-primary four columns" value="{submit_text}"/>
					</div>
					<script type="text/javascript">
						function setUrl(el,lang)
						{
							var val=$(el).val();
							val=val.replace(/[\s!#@\$%\^&\*><.;'"?\/\]\[\(\)\\]+/g," ").trim().replace(/\s+/g,"-");
							$("input[name='"+lang+"[cd_url]']").val(val);
						}
					</script>
				</form>

				<div style="display:none">
					<?php echo form_open(get_admin_category_details_link($category_id),array("id"=>"delete")); ?>
						<input type="hidden" name="post_type" value="delete_category"/>
						<input type="hidden" name="post_id" value="{category_id}"/>
					</form>

					<script type="text/javascript">
						$(function(){
							var parId="<?php echo $info[$selected_lang]['category_parent_id'];?>";
							$("#parent-category input[value="+parId+"]").prop("checked","checked");

							$("#parent-category input[name=category]").change(function()
							{
								$("input[name=category_parent_id]").val($(this).val());
							});

							$("#parent-category input[name=category][value="+parId+"]").trigger("change");
						});

	              	function deleteCategory()
						{
							if(!confirm("{are_you_sure_to_delete_this_category_text}"))
								return;

							$("form#delete").submit();
						}

						function addSubCategory()
						{
							$("form#delete input[name=post_type]").val("add_sub_category");
							$("form#delete").submit();
						}
					</script>
				</div>
			</div>
		<?php 
			}
		?>
		
		



	</div>
</div>